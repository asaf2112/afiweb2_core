<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\B2bSyncService;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchB2bProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'b2b:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'B2B Crawler: Tüm kategorileri ve sayfaları gezip ürünleri görseller ve teknik özelliklerle birlikte %15 kâr marjıyla kaydeder.';

    /**
     * Execute the console command.
     */
    public function handle(B2bSyncService $syncService, CurrencyService $currencyService)
    {
        $this->info('B2B Crawler (Full-Auto) başlatılıyor...');
        $totalSuccess = 0;

        try {
            // Önceki kur önbelleğini temizle ve yeni kuru al
            $this->info('Güncel Dolar Kuru çekiliyor...');
            $currencyService->clearCache();
            $currentRate = $currencyService->getUsdTryRate();
            $this->info('Güncel USD/TRY Kuru: ' . $currentRate);
            // Gerçek bir senaryoda burası B2B API veya HTML Scraping için base URL olur
            // Örnek: $categories = Http::get('https://b2b.example.com/api/categories')->json();
            
            // SİMÜLASYON: B2B sitesinden kategorileri çektiğimizi varsayıyoruz.
            $b2bCategories = [
                ['id' => '1', 'name' => 'İşlemciler', 'slug' => 'islemciler'],
                ['id' => '2', 'name' => 'Anakartlar', 'slug' => 'anakartlar'],
            ];

            // 1. Kategoriler Üzerinde Döngü (Tüm kategorileri gez)
            foreach ($b2bCategories as $category) {
                $this->info("Kategori taranıyor: " . $category['name']);
                
                $currentPage = 1;
                $hasMorePages = true;

                // 2. Sayfalama (Pagination) Döngüsü (Her kategorinin içindeki sayfaları gez)
                while ($hasMorePages) {
                    $this->line(" - Sayfa {$currentPage} taranıyor...");
                    
                    // Gerçek senaryoda:
                    // $response = Http::get("https://b2b.example.com/api/products?category={$category['slug']}&page={$currentPage}");
                    // $b2bProducts = $response->json('data');
                    // $hasMorePages = $response->json('current_page') < $response->json('last_page');
                    
                    // SİMÜLASYON: Sadece ilk sayfada 2'şer ürün varmış gibi davranıyoruz
                    $b2bProducts = $this->simulateScraping($category['name'], $currentPage);
                    
                    if (empty($b2bProducts)) {
                        $hasMorePages = false;
                        continue;
                    }

                    // Her sayfadaki ürünleri B2B servisine gönder
                    $successCount = $syncService->syncAll($b2bProducts);
                    $totalSuccess += $successCount;
                    
                    $this->info("   > {$successCount} ürün senkronize edildi.");

                    // Simülasyon gereği sadece 1 sayfa çalıştırıp bitiriyoruz
                    $hasMorePages = false; // Gerçekte yukarıdaki API kontrolüne göre döner
                    $currentPage++;
                    
                    // Sunucuyu yormamak için kısa bir bekleme (opsiyonel)
                    // sleep(1);
                }
            }

            // 3. İlişkili Ürünleri (Cross-Selling) Oluştur
            $this->info("Kategori bazlı tamamlayıcı ürünler eşleştiriliyor...");
            $syncService->associateCategoryProducts();

            $this->info("Senkronizasyon (Crawler) ve çapraz satış eşleştirmeleri tamamlandı! Toplam başarıyla işlenen ürün: {$totalSuccess}");

        } catch (\Exception $e) {
            $this->error('B2B Crawler Hatası: ' . $e->getMessage());
            Log::error('B2B Crawler Hatası: ' . $e->getMessage());
        }
    }

    /**
     * B2B Data Scraping Simülasyonu
     * Gerçek bir crawler burada DOMDocument veya Symfony DomCrawler kullanarak 
     * HTML tablolarını parse eder.
     */
    private function simulateScraping($categoryName, $page)
    {
        if ($categoryName === 'İşlemciler' && $page == 1) {
            return [
                [
                    'name' => 'INTEL COMETLAKE CORE I5 10400F 2.9GHz 12MB CACHE LGA1200 İŞLEMCİ',
                    'category_name' => $categoryName,
                    'price' => 120, // USD bazlı fiyat
                    'stock' => 20,
                    'condition_type' => 'new',
                    'image_url' => 'https://placehold.co/600x600?text=Intel+i5',
                    'description' => '', // Boş bıraktık, Servis teknik özelliklerden özet yapacak
                    'specifications' => [
                        'Soket Tipi' => 'LGA 1200',
                        'Çekirdek Sayısı' => '6 Çekirdek',
                        'İş Parçacığı Sayısı' => '12',
                        'Temel Frekans' => '2.9 GHz',
                        'Önbellek' => '12 MB Intel Smart Cache'
                    ]
                ]
            ];
        }

        if ($categoryName === 'Anakartlar' && $page == 1) {
            return [
                [
                    'name' => 'MSI B450 TOMAHAWK MAX AM4 DDR4 4133(OC) MHz ATX ANAKART',
                    'category_name' => $categoryName,
                    'price' => 140, // USD bazlı fiyat
                    'stock' => 12,
                    'condition_type' => 'new',
                    'image_url' => 'https://placehold.co/600x600?text=MSI+B450',
                    'description' => '', // Boş bıraktık
                    'specifications' => [
                        'Soket Tipi' => 'AM4',
                        'Yonga Seti' => 'AMD B450',
                        'RAM Tipi' => 'DDR4',
                        'Maksimum RAM' => '64 GB'
                    ]
                ]
            ];
        }

        return [];
    }
}
