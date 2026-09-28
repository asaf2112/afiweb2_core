<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'brand',
        'slug',
        'serial_number',
        'category_id',
        'condition_type',
        'usage_status',
        'price',
        'b2b_price_usd',
        'discount_price',
        'discount_end_date',
        'discount_expires_at',
        'stock',
        'description',

        'main_image',
        'specs',
        'socket',
        'ram_type',
        'tdp_watt',
        'badge',
        'is_bestseller',
        'is_featured',
        'is_read'
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->serial_number)) {
                $maxId = static::max('id') ?? 0;
                $product->serial_number = 'AFI-SRN-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'specs' => 'array',
        'discount_end_date' => 'datetime',
        'tdp_watt' => 'integer',
        'is_bestseller' => 'boolean',
        'is_featured' => 'boolean',
        'sales_count' => 'integer',
        'is_read' => 'boolean',
    ];

    public function getDiscountExpiresAtAttribute()
    {
        return $this->discount_end_date;
    }

    public function setDiscountExpiresAtAttribute($value)
    {
        $this->attributes['discount_end_date'] = $value ? \Carbon\Carbon::parse($value) : null;
    }

    /**
     * Özel Ürün Rozeti Renk ve İkon Teması
     */
    public function getBadgeStyleAttribute(): array
    {
        if (empty($this->badge)) {
            return [];
        }

        $badgeText  = trim($this->badge);
        $badgeLower = mb_strtolower($badgeText, 'UTF-8');

        if (str_contains($badgeLower, 'f/p') || str_contains($badgeLower, 'fiyat') || str_contains($badgeLower, 'canavar')) {
            return [
                'text'       => $badgeText,
                'bg_class'   => 'bg-gradient-to-r from-orange-500 to-amber-500 text-white border-orange-400/50 shadow-orange-500/30',
                'icon'       => 'fa-fire-flame-curved',
                'badge_html' => '<span class="bg-gradient-to-r from-orange-500 to-amber-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md border border-orange-400/50 animate-pulse"><i class="fa-solid fa-fire-flame-curved"></i> ' . e($badgeText) . '</span>'
            ];
        }

        if (str_contains($badgeLower, 'yayıncı') || str_contains($badgeLower, 'streamer') || str_contains($badgeLower, 'özel')) {
            return [
                'text'       => $badgeText,
                'bg_class'   => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-400/50 shadow-purple-500/30',
                'icon'       => 'fa-headset',
                'badge_html' => '<span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md border border-purple-400/50"><i class="fa-solid fa-headset"></i> ' . e($badgeText) . '</span>'
            ];
        }

        if (str_contains($badgeLower, 'gaming') || str_contains($badgeLower, '2k') || str_contains($badgeLower, '4k') || str_contains($badgeLower, 'oyun')) {
            return [
                'text'       => $badgeText,
                'bg_class'   => 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white border-emerald-400/50 shadow-emerald-500/30',
                'icon'       => 'fa-gamepad',
                'badge_html' => '<span class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md border border-emerald-400/50"><i class="fa-solid fa-gamepad"></i> ' . e($badgeText) . '</span>'
            ];
        }

        if (str_contains($badgeLower, 'tüken') || str_contains($badgeLower, 'son') || str_contains($badgeLower, 'fırsat')) {
            return [
                'text'       => $badgeText,
                'bg_class'   => 'bg-gradient-to-r from-red-600 to-rose-600 text-white border-red-400/50 shadow-red-500/30',
                'icon'       => 'fa-bolt',
                'badge_html' => '<span class="bg-gradient-to-r from-red-600 to-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md border border-red-400/50 animate-pulse"><i class="fa-solid fa-bolt"></i> ' . e($badgeText) . '</span>'
            ];
        }

        return [
            'text'       => $badgeText,
            'bg_class'   => 'bg-gradient-to-r from-yellow-500 to-amber-500 text-afiDark border-yellow-400/50 shadow-yellow-500/30',
            'icon'       => 'fa-star',
            'badge_html' => '<span class="bg-gradient-to-r from-yellow-500 to-amber-500 text-afiDark text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1 shadow-md border border-yellow-400/50"><i class="fa-solid fa-star"></i> ' . e($badgeText) . '</span>'
        ];
    }

    /**
     * Ürünün Oyuncu Kasası (Gaming PC) olup olmadığını kontrol eder.
     */
    public function getIsGamingPcAttribute(): bool
    {
        $catSlug = strtolower($this->category->slug ?? '');
        $catName = strtolower($this->category->name ?? '');
        if (str_contains($catSlug, 'oyuncu') || str_contains($catSlug, 'gaming') || str_contains($catName, 'oyuncu') || str_contains($catName, 'gaming')) {
            return true;
        }

        $badgeLower = mb_strtolower($this->badge ?? '', 'UTF-8');
        if (str_contains($badgeLower, 'gaming') || str_contains($badgeLower, 'oyuncu') || str_contains($badgeLower, 'yayıncı') || str_contains($badgeLower, 'streamer')) {
            return true;
        }

        $specs = is_array($this->specs) ? $this->specs : (json_decode($this->specs ?? '[]', true) ?? []);
        if (isset($specs['is_gaming']) && in_array(strtolower((string)$specs['is_gaming']), ['1', 'true', 'evet', 'yes'])) {
            return true;
        }

        $titleLower = mb_strtolower($this->title ?? $this->name ?? '', 'UTF-8');
        if (str_contains($titleLower, 'oyuncu kasası') || str_contains($titleLower, 'oyuncu bilgisayarı') || str_contains($titleLower, 'gaming pc') || str_contains($titleLower, 'gaming kasa')) {
            return true;
        }

        return false;
    }

    /**
     * Platform Bilgisi (INTEL veya AMD)
     */
    public function getEffectivePlatformAttribute(): ?string
    {
        $catSlug = strtolower($this->category->slug ?? '');
        if (in_array($catSlug, ['ssd', 'ssd-depolama', 'hdd', 'guc-kaynagi', 'bilgisayar-kasasi'])) {
            return null;
        }

        $brand = strtoupper($this->brand ?? '');
        $text  = strtoupper($this->title ?? $this->name ?? '');

        if (str_contains($brand, 'INTEL') || str_contains($text, 'INTEL') || str_contains($text, 'LGA') || str_contains($text, 'Z790') || str_contains($text, 'B760') || str_contains($text, 'H610') || str_contains($text, 'Z690') || str_contains($text, 'B660') || str_contains($text, 'Z890') || str_contains($text, 'B860')) {
            return 'INTEL';
        }

        if (str_contains($brand, 'AMD') || str_contains($text, 'AMD') || str_contains($text, 'RYZEN') || str_contains($text, 'AM5') || str_contains($text, 'AM4') || str_contains($text, 'B650') || str_contains($text, 'X670') || str_contains($text, 'A620') || str_contains($text, 'X870') || str_contains($text, 'B550') || str_contains($text, 'X570') || str_contains($text, 'A520') || str_contains($text, 'B450')) {
            return 'AMD';
        }

        $socket = $this->effective_socket;
        if ($socket) {
            if (in_array($socket, ['LGA1700', 'LGA1200', 'LGA1851', 'LGA1151', 'LGA2066'])) {
                return 'INTEL';
            }
            if (in_array($socket, ['AM5', 'AM4', 'TR4', 'sWRX8'])) {
                return 'AMD';
            }
        }

        return null;
    }

    /**
     * Soket Bilgisi (Örn: LGA1700, AM5, AM4, LGA1851, LGA1200)
     */
    public function getEffectiveSocketAttribute(): ?string
    {
        $catSlug = strtolower($this->category->slug ?? '');
        if (in_array($catSlug, ['ssd', 'ssd-depolama', 'hdd', 'guc-kaynagi', 'bilgisayar-kasasi', 'ram', 'ekran-karti'])) {
            return null;
        }

        $rawSocket = null;
        if (!empty($this->socket)) {
            $rawSocket = $this->socket;
        } else {
            $fromSpecs = $this->specs['socket'] ?? ($this->specs['Soket Tipi'] ?? ($this->specs['Soket'] ?? null));
            if (!empty($fromSpecs)) {
                $rawSocket = $fromSpecs;
            }
        }

        if ($rawSocket) {
            return strtoupper(str_replace([' ', '-', '_'], '', trim($rawSocket)));
        }

        $text = strtoupper($this->title ?? $this->name ?? '');

        // AM5
        if (str_contains($text, 'AM5') || str_contains($text, 'B650') || str_contains($text, 'X670') || str_contains($text, 'A620') || str_contains($text, 'X870')) {
            return 'AM5';
        }
        if (str_contains($text, 'RYZEN') && (str_contains($text, '7000') || str_contains($text, '9000') || str_contains($text, '7600') || str_contains($text, '7700') || str_contains($text, '7800') || str_contains($text, '7900') || str_contains($text, '7950') || str_contains($text, '9600') || str_contains($text, '9700') || str_contains($text, '9900') || str_contains($text, '9950') || str_contains($text, '7800X3D') || str_contains($text, '7950X3D'))) {
            return 'AM5';
        }

        // AM4
        if (str_contains($text, 'AM4') || str_contains($text, 'B550') || str_contains($text, 'X570') || str_contains($text, 'A520') || str_contains($text, 'B450') || str_contains($text, 'A320')) {
            return 'AM4';
        }
        if (str_contains($text, 'RYZEN') && (str_contains($text, '5000') || str_contains($text, '5600') || str_contains($text, '5700') || str_contains($text, '5800') || str_contains($text, '5900') || str_contains($text, '5950') || str_contains($text, '3600') || str_contains($text, '3700') || str_contains($text, '1600') || str_contains($text, '2600') || str_contains($text, '5700X3D') || str_contains($text, '5800X3D'))) {
            return 'AM4';
        }

        // LGA1851
        if (str_contains($text, 'LGA1851') || str_contains($text, '1851') || str_contains($text, 'Z890') || str_contains($text, 'B860')) {
            return 'LGA1851';
        }

        // LGA1700
        if (str_contains($text, 'LGA1700') || str_contains($text, '1700') || str_contains($text, 'Z790') || str_contains($text, 'B760') || str_contains($text, 'H610') || str_contains($text, 'Z690') || str_contains($text, 'B660') || str_contains($text, 'H670') || str_contains($text, '14700') || str_contains($text, '13600') || str_contains($text, '12400') || str_contains($text, '13700') || str_contains($text, '14900') || str_contains($text, '12900') || str_contains($text, '13400') || str_contains($text, '14400') || str_contains($text, '13900') || str_contains($text, '14600') || str_contains($text, '12700') || str_contains($text, '12600') || str_contains($text, '14100') || str_contains($text, '13100') || str_contains($text, '12100')) {
            return 'LGA1700';
        }

        // LGA1200
        if (str_contains($text, 'LGA1200') || str_contains($text, '1200') || str_contains($text, 'Z590') || str_contains($text, 'B560') || str_contains($text, 'H510') || str_contains($text, 'Z490') || str_contains($text, 'B460') || str_contains($text, '11700') || str_contains($text, '10400') || str_contains($text, '11400') || str_contains($text, '10700') || str_contains($text, '10900') || str_contains($text, '10100')) {
            return 'LGA1200';
        }

        return null;
    }

    /**
     * Bellek Tipi (Örn: DDR4, DDR5)
     */
    public function getEffectiveRamTypeAttribute(): ?string
    {
        $catSlug = strtolower($this->category->slug ?? '');
        if (in_array($catSlug, ['ssd', 'ssd-depolama', 'hdd', 'guc-kaynagi', 'bilgisayar-kasasi', 'ekran-karti'])) {
            return null;
        }

        $rawRam = null;
        if (!empty($this->ram_type)) {
            $rawRam = $this->ram_type;
        } else {
            $fromSpecs = $this->specs['ram_type'] ?? ($this->specs['Bellek Tipi'] ?? ($this->specs['RAM Tipi'] ?? null));
            if (!empty($fromSpecs)) {
                $rawRam = $fromSpecs;
            }
        }

        if ($rawRam) {
            $upper = strtoupper(str_replace([' ', '-', '_'], '', trim($rawRam)));
            if (str_contains($upper, 'DDR5')) return 'DDR5';
            if (str_contains($upper, 'DDR4')) return 'DDR4';
            if (str_contains($upper, 'DDR3')) return 'DDR3';
            return $upper;
        }

        $text = strtoupper($this->title ?? $this->name ?? '');
        if (str_contains($text, 'DDR5')) return 'DDR5';
        if (str_contains($text, 'DDR4')) return 'DDR4';
        if (str_contains($text, 'DDR3')) return 'DDR3';

        return null;
    }

    /**
     * Güç / TDP Tüketimi veya PSU Kapasitesi (Watt)
     */
    public function getEffectiveTdpWattAttribute(): int
    {
        if (!empty($this->tdp_watt) && $this->tdp_watt > 0) {
            return (int) $this->tdp_watt;
        }

        $fromSpecs = $this->specs['tdp_watt'] ?? ($this->specs['TDP'] ?? ($this->specs['Güç'] ?? 0));
        if (!empty($fromSpecs) && (int)$fromSpecs > 0) {
            return (int) $fromSpecs;
        }

        // Title parsing (e.g. 750W, 850W, 650W)
        $text = $this->title ?? $this->name ?? '';
        if (preg_match('/(\d{3,4})\s*w/i', $text, $m)) {
            return (int) $m[1];
        }

        return 0;
    }

    /**
     * Ekran Kartı Uzunluğu (mm)
     */
    public function getEffectiveGpuLengthAttribute(): int
    {
        $specs = $this->specs ?? [];
        if (!empty($specs['gpu_length'])) return (int)$specs['gpu_length'];
        if (!empty($specs['Kart Uzunluğu'])) return (int)$specs['Kart Uzunluğu'];

        $text = strtoupper($this->title ?? '');
        if (preg_match('/(\d{3})\s*mm/i', $text, $m)) {
            $val = (int)$m[1];
            if ($val >= 180 && $val <= 450) return $val;
        }

        if (str_contains($text, 'TRIPLE') || str_contains($text, '3 FAN') || str_contains($text, '3X') || str_contains($text, 'TRIO') || str_contains($text, 'STRIX') || str_contains($text, 'SUPRIM') || str_contains($text, '4080') || str_contains($text, '4090') || str_contains($text, '7900')) {
            return 330;
        }
        if (str_contains($text, 'DUAL') || str_contains($text, '2 FAN') || str_contains($text, '2X') || str_contains($text, 'TWIN') || str_contains($text, '4060') || str_contains($text, '3060') || str_contains($text, '6600')) {
            return 240;
        }

        return 0;
    }

    /**
     * Kasa Maksimum Ekran Kartı Desteği (mm)
     */
    public function getEffectiveMaxGpuLengthAttribute(): int
    {
        $specs = $this->specs ?? [];
        if (!empty($specs['max_gpu_length'])) return (int)$specs['max_gpu_length'];
        if (!empty($specs['Maksimum GPU Desteği'])) return (int)$specs['Maksimum GPU Desteği'];

        $text = strtoupper($this->title ?? '');
        if (preg_match('/max\s*(\d{3})\s*mm/i', $text, $m)) {
            return (int)$m[1];
        }

        if (str_contains($text, 'MINI') || str_contains($text, 'ITX') || str_contains($text, 'SLIM')) {
            return 290;
        }

        return 360; // Standart Mid/Full Tower Kasa desteği
    }

    /**
     * Karşılaştırma matrisinde kullanılacak ayırt edici teknik parametrelerin haritası
     */
    public function getDistinctiveSpecsAttribute(): array
    {
        $rawSpecs = is_array($this->specs) ? $this->specs : (json_decode($this->specs ?? '[]', true) ?? []);
        
        $normalized = [];
        foreach ($rawSpecs as $k => $v) {
            $val = is_array($v) ? implode(', ', $v) : (string)$v;
            $normalized[mb_strtolower(trim($k), 'UTF-8')] = trim($val);
        }

        $title = $this->title ?? '';

        // 1. Soket / Platform
        $socket = $this->effective_socket ?? ($normalized['socket'] ?? ($normalized['soket'] ?? ($normalized['soket tipi'] ?? null)));
        
        // 2. RAM Tipi & Kapasitesi
        $ramType = $this->effective_ram_type ?? ($normalized['ram_type'] ?? ($normalized['ram tipi'] ?? ($normalized['bellek tipi'] ?? null)));
        $ramCap = $normalized['ram'] ?? ($normalized['bellek'] ?? ($normalized['ram kapasitesi'] ?? null));
        if (!$ramCap && preg_match('/(\d+\s*GB)\s*(?:DDR\d)?/i', $title, $m)) {
            $ramCap = strtoupper($m[1]);
        }
        $ramDisplay = trim(($ramCap ? $ramCap . ' ' : '') . ($ramType ?: ''));

        // 3. TDP / Güç Tüketimi
        $tdp = $this->effective_tdp_watt;
        $tdpStr = $tdp > 0 ? "{$tdp} Watt" : ($normalized['tdp'] ?? ($normalized['güç tüketimi'] ?? ($normalized['psu'] ?? 'Standart')));

        // 4. İşlemci (CPU) Detayları
        $cpu = $normalized['cpu'] ?? ($normalized['işlemci'] ?? null);
        if (!$cpu && preg_match('/(Intel\s+Core\s+i\d-\d+\w*|AMD\s+Ryzen\s+\d+\s*\d+\w*|i\d-\d+\w*|Ryzen\s+\d\s+\d+\w*)/i', $title, $m)) {
            $cpu = $m[1];
        }

        // 5. Ekran Kartı (GPU) / VRAM
        $gpu = $normalized['gpu'] ?? ($normalized['ekran kartı'] ?? null);
        if (!$gpu && preg_match('/(RTX\s*\d{4}(?:\s*Ti|\s*Super)?|RX\s*\d{4}(?:\s*XT)?|GTX\s*\d{4})/i', $title, $m)) {
            $gpu = $m[1];
        }

        // 6. Depolama / Hız
        $storage = $normalized['storage'] ?? ($normalized['depolama'] ?? ($normalized['ssd'] ?? ($normalized['hdd'] ?? null)));
        $readSpeed = $normalized['read_speed'] ?? ($normalized['okuma hızı'] ?? null);
        if (!$readSpeed && preg_match('/(\d{3,4})\s*MB\/s/i', $title, $m)) {
            $readSpeed = $m[1] . ' MB/s';
        }
        $storageDisplay = $storage ?: ($readSpeed ? "NVMe SSD ({$readSpeed})" : null);

        // 7. Maks GPU Uzunluk Desteği
        $maxGpu = $this->effective_max_gpu_length;
        $maxGpuStr = $maxGpu > 0 ? "{$maxGpu} mm" : 'Standart';

        return [
            'Soket / Platform'       => $socket ?: 'Dahili / Evrensel',
            'İşlemci (CPU)'          => $cpu ?: 'Dahili / Sistem Özel',
            'Ekran Kartı (GPU)'      => $gpu ?: 'Dahili / Sistem Özel',
            'RAM Bellek Mimarisi'    => $ramDisplay ?: 'Varsayılan',
            'TDP / Güç Tüketimi'     => $tdpStr,
            'Depolama & Hız'        => $storageDisplay ?: 'Dahili / Belirtilmedi',
            'Maks GPU Uzunluk Desteği' => $maxGpuStr,
        ];
    }

    /**
     * Kritik Stok Seviyesi Kontrolü (5 ve altı)
     */
    public function getIsCriticalStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= 5;
    }

    /**
     * Dinamik Stok Rozeti Detayları
     */
    public function getStockBadgeAttribute(): array
    {
        if ($this->stock <= 0) {
            return [
                'label'          => 'Stokta Yok',
                'bg_class'       => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                'dot_class'      => 'bg-gray-500',
                'is_out_of_stock'=> true,
                'is_critical'    => false,
            ];
        }

        if ($this->is_critical_stock) {
            return [
                'label'          => "Son {$this->stock} Ürün - Tükeniyor!",
                'bg_class'       => 'bg-amber-500/20 text-amber-300 border-amber-500/40 animate-pulse',
                'dot_class'      => 'bg-amber-400',
                'is_out_of_stock'=> false,
                'is_critical'    => true,
            ];
        }

        return [
            'label'          => 'Stokta Var',
            'bg_class'       => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
            'dot_class'      => 'bg-emerald-400',
            'is_out_of_stock'=> false,
            'is_critical'    => false,
        ];
    }

    /**
     * Dinamik Kargolama & Lojistik Bilgisi (Saat 16:00 Cutoff)
     */
    public function getShippingInfoAttribute(): array
    {
        $now = \Carbon\Carbon::now('Europe/Istanbul');
        
        // Hafta içi saat 16:00 öncesi verilen siparişler aynı gün kargolanır
        if ($now->hour < 16 && !$now->isWeekend()) {
            return [
                'title'       => 'Bugün Kargoda',
                'subtext'     => 'Saat 16:00\'ya kadar sipariş verirseniz bugün kargoda!',
                'icon'        => 'fa-truck-fast',
                'badge_class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                'is_today'    => true,
            ];
        }

        return [
            'title'       => 'Yarın Kargoda',
            'subtext'     => 'Siparişiniz ilk iş gününde kargoya teslim edilir.',
            'icon'        => 'fa-truck',
            'badge_class' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
            'is_today'    => false,
        ];
    }

    public function getIsDiscountActiveAttribute()
    {
        if (!$this->discount_price || (float)$this->discount_price <= 0 || (float)$this->discount_price >= (float)$this->price || !$this->discount_end_date) {
            return false;
        }
        $endDate = $this->discount_end_date instanceof \Carbon\Carbon 
            ? $this->discount_end_date 
            : \Carbon\Carbon::parse($this->discount_end_date);

        return $endDate->isFuture();
    }

    public function getFinalPriceAttribute()
    {
        return $this->is_discount_active ? $this->discount_price : $this->price;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Cross-selling için ilişkili ürünler
     */
    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id');
    }

    /**
     * Anakart Bellek (RAM) Slot Sayısı (Varsayılan 4, ITX/Micro-ATX için 2)
     */
    public function getEffectiveRamSlotsAttribute(): int
    {
        $specs = $this->specs ?? [];
        if (!empty($specs['ram_slots'])) return (int)$specs['ram_slots'];
        if (!empty($specs['RAM Yuvası'])) return (int)$specs['RAM Yuvası'];

        $text = strtoupper($this->title ?? '');
        if (str_contains($text, 'ITX') || str_contains($text, 'MINI-ITX') || str_contains($text, 'H610M-K') || str_contains($text, 'A520M-K')) {
            return 2;
        }

        return 4; // Standart ATX / M-ATX Anakartlar 4 yuvalıdır
    }

    /**
     * Anakart M.2 SSD Slot Sayısı (Varsayılan 2)
     */
    public function getEffectiveM2SlotsAttribute(): int
    {
        $specs = $this->specs ?? [];
        if (!empty($specs['m2_slots'])) return (int)$specs['m2_slots'];
        if (!empty($specs['M.2 Yuvası'])) return (int)$specs['M.2 Yuvası'];

        $text = strtoupper($this->title ?? '');
        if (str_contains($text, 'Z790') || str_contains($text, 'X670') || str_contains($text, 'MAXIMUS') || str_contains($text, 'STRIX')) {
            return 4;
        }

        return 2; // Standart Anakartlar 2 adet M.2 slotuna sahiptir
    }

}
