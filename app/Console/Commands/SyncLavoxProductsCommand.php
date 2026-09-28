<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LavoxSupplierService;

class SyncLavoxProductsCommand extends Command
{
    /**
     * Konsol komut adı ve imzası
     */
    protected $signature = 'lavox:sync {--dry-run : Sadece verileri kontrol et, veritabanını güncelleme}';

    /**
     * Komut açıklaması
     */
    protected $description = 'LAVOX Toptancı sisteminden ürün verilerini, stoklarını ve fiyatlarını otomatik senkronize eder.';

    /**
     * Komut çalıştırıldığında tetiklenen metod
     */
    public function handle(LavoxSupplierService $lavoxService)
    {
        $this->info('🚀 LAVOX Toptancı Senkronizasyonu Başlatılıyor...');

        if ($this->option('dry-run')) {
            $this->warn('⚠️ Dry-run modu aktif: Veritabanı güncellenmeyecek.');
        }

        $isDryRun = (bool) $this->option('dry-run');
        $startTime = microtime(true);
        $stats = $lavoxService->syncProducts($isDryRun);
        $duration = round(microtime(true) - $startTime, 2);

        $this->newLine();
        $this->table(
            ['Metrik', 'Değer'],
            [
                ['Çekilen Toplam Ürün', $stats['total_fetched']],
                ['Yeni Eklendi', $stats['created']],
                ['Güncellendi', $stats['updated']],
                ['Atlandı / Değişmedi', $stats['skipped']],
                ['Hatalar', $stats['errors']],
                ['İşlem Süresi', "{$duration} saniye"],
            ]
        );

        $this->info('✅ LAVOX Senkronizasyon İşlemi Tamamlandı!');

        return Command::SUCCESS;
    }
}
