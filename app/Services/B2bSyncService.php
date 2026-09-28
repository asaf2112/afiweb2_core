<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\PriceLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class B2bSyncService
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * B2B kaynağından gelen tek bir ürünü sisteme senkronize eder.
     * 
     * @param array $b2bProduct B2B'den gelen ürün verisi dizisi
     * @return Product|null
     */
    public function syncProduct(array $b2bProduct)
    {
        try {
            // 1. Kategori Kontrolü ve Dinamik Oluşturma
            $categoryName = trim($b2bProduct['category_name'] ?? 'Diğer');

            // Veritabanındaki kategorilerle akıllı eşleşme (Normalizasyon)
            $category = Category::get()->first(function($cat) use ($categoryName) {
                $dbName = trim(mb_strtolower($cat->name, 'UTF-8'));
                $b2bName = trim(mb_strtolower($categoryName, 'UTF-8'));
                
                // 1. Birebir eşleşme
                if ($dbName === $b2bName) return true;
                
                // 2. Slug eşleşmesi
                $dbSlug = Str::slug($cat->name);
                $b2bSlug = Str::slug($categoryName);
                if ($dbSlug === $b2bSlug) return true;

                // 3. Basit çoğul eki temizliği (ler, lar)
                $cleanDb = preg_replace('/(ler|lar)$/', '', $dbSlug);
                $cleanB2b = preg_replace('/(ler|lar)$/', '', $b2bSlug);
                if ($cleanDb === $cleanB2b) return true;
                
                return false;
            });

            // Bulunamadıysa yeni oluştur
            if (!$category) {
                $categorySlug = Str::slug($categoryName);
                $category = Category::create([
                    'slug' => $categorySlug,
                    'name' => $categoryName
                ]);
            }

            // 2. Fiyat ve Kur Hesaplama (USD -> TRY %15 Kâr)
            $b2bPriceUsd = (float) ($b2bProduct['price'] ?? 0); // Varsayım: B2B API Dolar gönderiyor
            $currentExchangeRate = $this->currencyService->getUsdTryRate();
            $sellingPriceTry = ($b2bPriceUsd * $currentExchangeRate) * 1.15;

            // 3. Kart Arkası Kısa Açıklama (Description)
            // Eğer B2B'den description gelmemişse, teknik özelliklerin bir özetini çıkar veya başlığı yaz
            $description = $b2bProduct['description'] ?? '';
            if (empty($description)) {
                if (!empty($b2bProduct['specifications'])) {
                    // Teknik özellikleri virgülle ayırıp kısa özet yapalım
                    $summary = [];
                    foreach (array_slice($b2bProduct['specifications'], 0, 3) as $k => $v) {
                        $summary[] = "$k: $v";
                    }
                    $description = implode(' | ', $summary);
                } else {
                    $description = "Orijinal " . $categoryName . " ürünü: " . $b2bProduct['name'];
                }
            }

            // 4. Ürünü Veritabanına Ekle veya Güncelle
            // Ürün isminden benzersiz bir slug oluşturuyoruz. B2B entegrasyonlarında normalde SKU kullanılır ancak veritabanımızda slug eşsiz.
            $productSlug = Str::slug($b2bProduct['name']);

            $existingProduct = Product::where('slug', $productSlug)->first();
            $oldPriceTry = $existingProduct ? $existingProduct->price : null;

            $product = Product::updateOrCreate(
                ['slug' => $productSlug], // Arama kriteri: Slug (Veritabanında unique)
                [
                    'category_id' => $category->id,
                    'title' => $b2bProduct['name'],
                    'description' => $description,
                    'b2b_price_usd' => $b2bPriceUsd,
                    'price' => $sellingPriceTry, // Hesaplanmış TRY satış fiyatı
                    'stock' => $b2bProduct['stock'] ?? 0,
                    'condition_type' => $b2bProduct['condition_type'] ?? 'new',
                    'main_image' => $b2bProduct['image_url'] ?? null,
                    'specs' => $b2bProduct['specifications'] ?? null,
                ]
            );

            // Fiyat History (Geçmiş) Güncellemesi
            if (!$existingProduct || $oldPriceTry != $sellingPriceTry) {
                PriceLog::create([
                    'product_id' => $product->id,
                    'b2b_price_usd' => $b2bPriceUsd,
                    'exchange_rate' => $currentExchangeRate,
                    'old_price_try' => $oldPriceTry,
                    'new_price_try' => $sellingPriceTry,
                ]);

                // 5. Fiyat Alarmı Kontrolü (Monitor)
                $alerts = \App\Models\PriceAlert::where('product_id', $product->id)
                    ->where('is_notified', false)
                    ->where('target_price', '>=', $sellingPriceTry)
                    ->get();

                foreach ($alerts as $alert) {
                    $contact = $alert->user_id ? ($alert->user->email ?? $alert->user->phone) : $alert->contact_info;
                    Log::info("Fiyat Alarmı Tetiklendi: [{$product->title}] fiyatı {$sellingPriceTry} ₺'ye düştü. Bildirim Gönderilen: {$contact}");
                    
                    // TODO: İleri aşamada Mail veya SMS servisi bağlanabilir
                    
                    $alert->is_notified = true;
                    $alert->save();
                }
            }

            return $product;

        } catch (\Exception $e) {
            Log::error("B2B Ürün Senkronizasyon Hatası: " . $e->getMessage(), ['product' => $b2bProduct]);
            return null;
        }
    }

    /**
     * Örnek bir toplu senkronizasyon metodu (XML veya API'den gelen liste için)
     */
    public function syncAll(array $b2bProductList)
    {
        $successCount = 0;
        foreach ($b2bProductList as $item) {
            $product = $this->syncProduct($item);
            if ($product) {
                $successCount++;
            }
        }
        return $successCount;
    }

    /**
     * Tüm ürünleri kategori bazlı eşleştirip product_related tablosuna işler.
     * Bu sayede "Birlikte Alabileceğiniz Ürünler" modülü tetiklenmiş olur.
     */
    public function associateCategoryProducts()
    {
        $categories = Category::with('products')->get();

        foreach ($categories as $category) {
            $products = $category->products;

            foreach ($products as $product) {
                // Aynı kategorideki, kendisi dışındaki diğer ürünlerden rastgele 3 tanesini al
                $relatedIds = $products->where('id', '!=', $product->id)
                                       ->random(min(3, $products->count() - 1))
                                       ->pluck('id');

                if ($relatedIds->isNotEmpty()) {
                    // Mevcut eşleşmeleri bozmamak için syncWithoutDetaching kullanıyoruz.
                    $product->relatedProducts()->syncWithoutDetaching($relatedIds);
                }
            }
        }
    }
}
