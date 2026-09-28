<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LavoxSupplierService
{
    /**
     * Lavox API veya XML Entegrasyon Konfigürasyonu
     */
    protected string $apiUrl;
    protected string $apiKey;
    protected float $profitMarginPercentage;

    public function __construct()
    {
        $this->apiUrl = config('services.lavox.api_url', 'https://api.lavox.com.tr/v1/products');
        $this->apiKey = config('services.lavox.api_key', env('LAVOX_API_KEY', ''));
        $this->profitMarginPercentage = (float) config('services.lavox.profit_margin', env('LAVOX_PROFIT_MARGIN', 15)); // %15 Kar Marjı varsayılan
    }

    /**
     * Lavox servisinden ürün verilerini çeker ve veritabanıyla senkronize eder.
     *
     * @return array Senkronizasyon özet istatistikleri
     */
    public function syncProducts(bool $isDryRun = false): array
    {
        $stats = [
            'total_fetched' => 0,
            'created'       => 0,
            'updated'       => 0,
            'skipped'       => 0,
            'errors'        => 0,
        ];

        try {
            Log::info("LAVOX Senkronizasyon Başlatıldı. API: {$this->apiUrl} (DryRun: " . ($isDryRun ? 'Evet' : 'Hayır') . ")");

            // 1. Ürün Verisi Çekme (REST API / XML Response)
            $productsData = $this->fetchProductsFromLavox();

            if (empty($productsData)) {
                Log::warning("LAVOX Entegrasyonu: Servisten boş veya geçersiz yanıt dönüldü.");
                return $stats;
            }

            $stats['total_fetched'] = count($productsData);

            // 2. Kategorileri Önceden Eşleştirme İçin Önbelleğe Al
            $categories = Category::all()->pluck('id', 'slug')->toArray();

            // 3. Ürünleri Tek Tek İşle ve Veritabanına Yansıt
            foreach ($productsData as $rawItem) {
                try {
                    $result = $this->processSingleProduct($rawItem, $categories, $isDryRun);
                    if ($result === 'created') {
                        $stats['created']++;
                    } elseif ($result === 'updated') {
                        $stats['updated']++;
                    } else {
                        $stats['skipped']++;
                    }
                } catch (\Throwable $e) {
                    $stats['errors']++;
                    Log::error("LAVOX Ürün İşleme Hatası [SKU: " . ($rawItem['sku'] ?? 'Bilinmiyor') . "]: " . $e->getMessage());
                }
            }

            Log::info("LAVOX Senkronizasyonu Tamamlandı.", $stats);
        } catch (\Throwable $e) {
            Log::critical("LAVOX Senkronizasyonu Genel Hata: " . $e->getMessage(), ['exception' => $e]);
        }

        return $stats;
    }

    /**
     * Lavox API'sinden veya XML/JSON akışından verileri çeker.
     */
    protected function fetchProductsFromLavox(): array
    {
        // Not: Canlı API veya XML bağlantısı sağlandığında bu alan gerçek HTTP isteği yapar.
        if (empty($this->apiKey) && !app()->isLocal()) {
            Log::error("LAVOX API Key eksik!");
            return [];
        }

        // Örnek XML / JSON HTTP İsteği Simülasyonu / Taslağı
        /*
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
        ])->timeout(30)->get($this->apiUrl);

        if ($response->successful()) {
            return $response->json('data') ?? [];
        }
        */

        // Taslak / Geliştirme Amaçlı Örnek Veri Şeması Taslağı
        return $this->getMockLavoxData();
    }

    /**
     * Tekil ürün verisini işler ve veritabanına yansıtır.
     */
    protected function processSingleProduct(array $item, array &$categories, bool $isDryRun = false): string
    {
        $sku = trim($item['sku'] ?? $item['barcode'] ?? '');
        if (empty($sku)) {
            return 'skipped';
        }

        $title = trim($item['name'] ?? $item['title'] ?? '');
        $costPrice = (float) ($item['cost_price'] ?? $item['wholesale_price'] ?? 0);
        
        // Kar marjını ekleyerek satış fiyatını hesapla
        $calculatedPrice = $costPrice > 0 
            ? round($costPrice * (1 + ($this->profitMarginPercentage / 100)), 2)
            : (float) ($item['retail_price'] ?? 0);

        $stock = (int) ($item['stock_quantity'] ?? $item['stock'] ?? 0);
        $categorySlug = Str::slug($item['category_name'] ?? 'bilesenler');

        // Kategori ID Eşleme (Yoksa 'Bileşenler' veya Varsayılan Kategori)
        $categoryId = $categories[$categorySlug] ?? (reset($categories) ?: 1);

        $productData = [
            'title'          => $title,
            'price'          => $calculatedPrice,
            'stock'          => $stock,
            'category_id'    => $categoryId,
            'serial_number'  => $sku,
            'specifications' => is_array($item['attributes'] ?? null) 
                ? json_encode($item['attributes'], JSON_UNESCAPED_UNICODE) 
                : ($item['description'] ?? null),
            'description'    => $item['long_description'] ?? $title,
            'main_image'     => $item['image_url'] ?? null,
        ];

        $existingProduct = Product::where('serial_number', $sku)->first();

        if ($existingProduct) {
            if (!$isDryRun) {
                $existingProduct->update([
                    'price' => $calculatedPrice,
                    'stock' => $stock,
                    'title' => $title,
                    'category_id' => $categoryId,
                ]);
            }
            return 'updated';
        } else {
            if (!$isDryRun) {
                $productData['slug'] = Str::slug($title);
                Product::create($productData);
            }
            return 'created';
        }
    }

    /**
     * Geliştirme ortamı için Lavox veri yapısı taslağı.
     */
    protected function getMockLavoxData(): array
    {
        return [
            [
                'sku' => 'LVX-GPU-4070TI',
                'name' => 'Lavox RTX 4070 Ti Super 16GB Ekran Kartı',
                'wholesale_price' => 28500.00,
                'retail_price' => 34500.00,
                'stock_quantity' => 12,
                'category_name' => 'bilesenler',
                'image_url' => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80',
                'attributes' => [
                    'Bellek Kapasitesi' => '16 GB',
                    'Bellek Tipi'       => 'GDDR6X',
                    'Arayüz'            => '256-Bit',
                ]
            ],
            [
                'sku' => 'LVX-SSD-2TB-NVME',
                'name' => 'Lavox Gen4 M.2 NVMe SSD 2TB (7300MB/s)',
                'wholesale_price' => 4200.00,
                'retail_price' => 5100.00,
                'stock_quantity' => 25,
                'category_name' => 'ssd-depolama',
                'image_url' => 'https://images.unsplash.com/photo-1597872240959-29aea88c5c85?w=600&q=80',
                'attributes' => [
                    'Kapasite'   => '2 TB',
                    'Okuma Hızı' => '7300 MB/s',
                    'Yazma Hızı' => '6800 MB/s',
                ]
            ]
        ];
    }
}
