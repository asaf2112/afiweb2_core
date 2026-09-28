@extends('layouts.app')

@section('title', ($product->title ?? 'Örnek Ürün') . ' | Afi Bilişim')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? ($product->title . ' — Afi Bilişim\'de güvenli alışveriş. Test edilmiş, garantili ürün.')), 155))
@section('og_type', 'product')
@section('og_url', route('products.show', $product->slug))
@section('og_title', ($product->title ?? 'Ürün') . ' | Afi Bilişim')
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 155, '...'))
@php
    $_ogImg = '';
    if (!empty($product->main_image)) {
        $_ogImg = \Illuminate\Support\Str::startsWith($product->main_image, ['http','https'])
            ? $product->main_image
            : asset($product->main_image);
    }
@endphp
@section('og_image', $_ogImg ?: asset('images/og-default.jpg'))

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Product",
  "name": "{{ addslashes($product->title ?? '') }}",
  "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 200)) }}",

  "image": "{{ $_ogImg ?: asset('images/og-default.jpg') }}",
  "brand": {"@@type": "Brand", "name": "Afi Bilişim"},
  "sku": "AFI-{{ $product->id ?? 0 }}",
  "offers": {
    "@@type": "Offer",
    "url": "{{ route('products.show', $product->slug) }}",
    "priceCurrency": "TRY",
    "price": "{{ $product->final_price ?? $product->price ?? 0 }}",
    "availability": "{{ ($product->stock ?? 1) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "seller": {"@@type": "Organization", "name": "Afi Bilişim"}
  },
  @if(isset($product) && $product->approvedReviews && $product->approvedReviews->count() > 0)
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ number_format($product->approvedReviews->avg('rating'), 1) }}",
    "reviewCount": "{{ $product->approvedReviews->count() }}"
  },
  @endif
  "condition": "{{ $product->condition_type === 'used' ? 'https://schema.org/UsedCondition' : 'https://schema.org/NewCondition' }}"
}
</script>

@endpush

@section('content')
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 py-3 md:py-6">
        <p class="text-gray-500 text-xs sm:text-sm font-medium truncate">
            <a href="{{ route('home') ?? '#' }}" class="hover:text-yellow-600">Ana Sayfa</a> 
            <i class="fa-solid fa-angle-right mx-1.5 text-[10px]"></i> 
            {{ $product->category->name ?? 'Kategori' }} 
            <i class="fa-solid fa-angle-right mx-1.5 text-[10px]"></i> 
            <span class="text-afiDark font-bold">{{ $product->title ?? 'Örnek Ürün Başlığı' }}</span>
        </p>
    </div>

    <!-- Product Detail Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 pb-6 md:pb-16">
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-12 flex flex-col lg:flex-row gap-6 md:gap-12">
            
            <!-- Left: Image Gallery -->
            <div class="lg:w-1/2">
                @php
                    // Varsayılan (Placeholder) görsel
                    $placeholder = 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=800&q=80';
                    $imageUrl = $placeholder;
                    
                    if (!empty($product->main_image)) {
                        if (\Illuminate\Support\Str::startsWith($product->main_image, ['http://', 'https://'])) {
                            $imageUrl = $product->main_image;
                        } else {
                            $imageUrl = asset($product->main_image);
                        }
                    }
                @endphp
                <div class="relative bg-gray-50 rounded-xl md:rounded-2xl h-[260px] sm:h-[340px] md:h-[400px] flex items-center justify-center p-4 md:p-8 mb-3 md:mb-4 border border-gray-100 overflow-hidden">
                    @if(isset($product) && $product->condition_type === 'used')
                        <div class="absolute top-4 left-4 md:top-6 md:left-6 z-10 bg-gradient-btn text-afiDark text-xs md:text-sm font-black px-3 py-1.5 md:px-4 md:py-2 rounded shadow-lg transform -rotate-3">
                            <i class="fa-solid fa-recycle"></i> 2. EL ÜRÜN
                        </div>
                    @else
                        <div class="absolute top-4 left-4 md:top-6 md:left-6 z-10 bg-green-100 text-green-700 text-xs md:text-sm font-bold px-3 py-1.5 md:px-4 md:py-2 rounded shadow-sm">
                            <i class="fa-solid fa-shield-check"></i> Sıfır Ürün
                        </div>
                    @endif
                    <img src="{{ $imageUrl }}" alt="{{ $product->title ?? 'Ürün Görseli' }}" class="object-contain h-full mix-blend-multiply drop-shadow-sm transition-transform duration-500 hover:scale-110" fetchpriority="high" decoding="async">
                </div>
                <!-- Thumbnail Gallery -->
                <div class="flex gap-2.5 sm:gap-4">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 bg-white rounded-xl border-2 border-yellow-500 p-1.5 sm:p-2 cursor-pointer shadow-sm">
                        <img src="{{ $imageUrl }}" alt="Thumb" class="w-full h-full object-contain mix-blend-multiply" loading="lazy" decoding="async">
                    </div>
                    <div class="w-16 h-16 sm:w-24 sm:h-24 bg-gray-50 rounded-xl border border-gray-200 p-1.5 sm:p-2 cursor-pointer hover:border-yellow-400 transition shadow-sm">
                        <div class="w-full h-full bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs sm:text-base"><i class="fa-solid fa-image"></i></div>
                    </div>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="lg:w-1/2 flex flex-col justify-center">
                <!-- Isolated Badges Header Container -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <!-- 1. Otomatik Durum Etiketi (Sıfır / İkinci El) -->
                    @if(isset($product))
                        <span class="{{ $product->condition_type == 'new' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-blue-100 text-blue-800 border-blue-300' }} text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5 border shadow-sm">
                            @if($product->condition_type == 'new')
                                <i class="fa-solid fa-shield-check"></i> Sıfır
                            @else
                                <i class="fa-solid fa-recycle"></i> İkinci El
                            @endif
                        </span>
                    @endif

                    <!-- 2. Seri Numarası (SKU) Rozeti -->
                    @if(isset($product) && !empty($product->serial_number))
                        <span class="bg-amber-50 text-amber-900 border border-amber-300/80 text-xs font-mono font-black px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm" title="Benzersiz Seri Numarası / SKU">
                            <i class="fa-solid fa-barcode text-amber-600"></i> {{ $product->serial_number }}
                        </span>
                    @endif

                    <!-- 3. Özel Admin Rozeti (Badge) -->
                    @if(isset($product) && !empty($product->badge))
                        @if(!empty($product->badge_style))
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black shadow-sm border {{ $product->badge_style['bg_class'] }}">
                                <i class="fa-solid {{ $product->badge_style['icon'] }}"></i>
                                {{ $product->badge_style['text'] }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black shadow-sm border bg-gradient-to-r from-yellow-500 to-amber-500 text-afiDark border-yellow-400/50">
                                <i class="fa-solid fa-star"></i>
                                {{ $product->badge }}
                            </span>
                        @endif
                    @endif

                    <!-- 4. Stok Durum Uyarısı -->
                    @if(isset($product) && $product->stock > 0 && $product->stock <= 5)
                        <span class="bg-amber-100 text-amber-800 border border-amber-300 text-xs font-black px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm animate-pulse">
                            <i class="fa-solid fa-fire text-red-600"></i> Son {{ $product->stock }} Ürün!
                        </span>
                    @elseif(isset($product) && $product->stock <= 0)
                        <span class="bg-red-100 text-red-700 border border-red-300 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-circle-xmark"></i> Stokta Yok
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl md:text-4xl font-heading font-black text-afiDark mb-2">{{ $product->title ?? 'Örnek Ürün Başlığı' }}</h1>
                
                <!-- Rating Summary -->
                @php
                    $avgRating = isset($product) ? ($product->approvedReviews->avg('rating') ?? 4.5) : 4.5;
                    $reviewCount = isset($product) && isset($product->approvedReviews) ? $product->approvedReviews->count() : 24;
                @endphp
                <div class="flex items-center gap-2 mb-6">
                    <div class="text-yellow-500 text-lg">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $avgRating)
                                <i class="fa-solid fa-star"></i>
                            @elseif($i - 0.5 <= $avgRating)
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-500 font-medium text-sm">({{ number_format($avgRating, 1) }} Puan - {{ $reviewCount }} Yorum)</span>
                </div>

                @if(isset($product) && $product->condition_type === 'used' && $product->usage_status)
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-xl mb-6">
                        <h4 class="font-bold flex items-center gap-2 mb-1"><i class="fa-solid fa-clipboard-check"></i> Kullanım Durumu</h4>
                        <p class="text-sm">{{ $product->usage_status }}</p>
                    </div>
                @endif

                <!-- Dinamik Stok Bilgilendirme Rozeti -->
                @if(isset($product))
                    @if($product->stock > 0 && $product->stock <= 5)
                        <div class="bg-amber-500/10 border border-amber-500/40 text-amber-900 p-4 rounded-2xl mb-6 flex items-center gap-3 animate-pulse">
                            <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                                <i class="fa-solid fa-fire"></i>
                            </span>
                            <div>
                                <h4 class="font-black text-sm text-amber-950">Son {{ $product->stock }} Ürün - Tükeniyor!</h4>
                                <p class="text-xs text-amber-800 font-medium">Bu üründen stoklarımızda yalnızca {{ $product->stock }} adet kaldı.</p>
                            </div>
                        </div>
                    @elseif($product->stock <= 0)
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </span>
                            <div>
                                <h4 class="font-black text-sm">Stokta Bulunmuyor</h4>
                                <p class="text-xs text-red-600">Bu ürün şu anda tükendi. Fiyat alarmı kurarak haber alabilirsiniz.</p>
                            </div>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3.5 py-1.5 rounded-full text-xs font-bold mb-6">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Stokta Var (Hızlı Teslimat)
                        </div>
                    @endif
                @endif

                <div class="flex items-center justify-between gap-4 mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-4xl md:text-5xl font-black text-afiDark">
                        {{ isset($product) ? number_format($product->final_price, 2, ',', '.') : '11.200,00' }} <span class="text-2xl">₺</span>
                        @if(isset($product) && $product->is_discount_active)
                            <span class="text-base text-gray-400 line-through font-normal block">{{ number_format($product->price, 2, ',', '.') }} ₺</span>
                        @endif
                    </div>

                    @if(isset($product) && !empty($product->serial_number))
                        <div class="bg-white border border-amber-400/40 px-3.5 py-2 rounded-xl flex items-center gap-2.5 shadow-sm">
                            <i class="fa-solid fa-barcode text-amber-500 text-2xl"></i>
                            <div class="text-left">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-gray-400 leading-none">Seri No / SKU</span>
                                <span class="text-xs font-mono font-black text-afiDark">{{ $product->serial_number }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Dinamik Lojistik Kargo Bilgisi -->
                <div class="p-4 rounded-2xl border bg-gray-50 border-gray-200 space-y-1 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500/20 text-yellow-700 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid {{ $product->shipping_info['icon'] }}"></i>
                    </div>
                    <div>
                        <h5 class="font-black text-sm text-afiDark">{{ $product->shipping_info['title'] }}</h5>
                        <p class="text-xs text-gray-600">{{ $product->shipping_info['subtext'] }}</p>
                    </div>
                </div>

                <div class="flex gap-3 mb-4">
                    <button id="add-to-cart-btn" data-product-id="{{ $product->id }}" {{ (isset($product) && $product->stock <= 0) ? 'disabled' : '' }} class="bg-gradient-btn flex-1 text-afiDark font-bold py-4 rounded-xl text-lg shadow-xl shadow-yellow-500/20 hover:-translate-y-1 transition-transform relative overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> <span id="cart-btn-text">{{ (isset($product) && $product->stock <= 0) ? 'Tükendi' : 'Sepete Ekle' }}</span>
                        </span>
                    </button>
                    @php
                        $isFavorited = auth()->check() && auth()->user()->favorites()->where('product_id', $product->id)->exists();
                    @endphp
                    <button type="button" class="favorite-toggle-btn {{ $isFavorited ? 'bg-red-50 text-red-500' : 'bg-gray-100 text-gray-400' }} hover:bg-red-100 hover:text-red-600 px-5 rounded-xl font-bold transition-colors shadow-sm" data-product-id="{{ $product->id }}" onclick="toggleFavorite(this, {{ $product->id }})" title="Favorilere Ekle">
                        <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart text-xl"></i>
                    </button>
                    <button type="button" class="bg-gray-100 text-gray-700 hover:bg-yellow-500 hover:text-slate-950 px-5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm shadow-sm" onclick="toggleCompare({{ $product->id }})" title="Karşılaştır">
                        <i class="fa-solid fa-scale-balanced text-lg"></i>
                        <span class="hidden sm:inline">Karşılaştır</span>
                    </button>
                </div>

                <button type="button" onclick="document.getElementById('price-alert-modal').classList.remove('hidden')" class="w-full bg-white border-2 border-yellow-500 text-yellow-600 font-bold py-3 rounded-xl shadow-sm hover:bg-yellow-50 hover:text-yellow-700 transition flex items-center justify-center gap-2 mb-8">
                    <i class="fa-solid fa-bell"></i> Fiyat Alarmı Kur
                </button>
                
                <div class="text-sm text-gray-500 space-y-3 pt-6 border-t border-gray-100">
                    <p><i class="fa-solid fa-shield-halved w-6 text-yellow-500"></i> <strong>Afi Güvencesi:</strong> İkinci el ürünlerde 3 ay mağaza garantisi, sıfır ürünlerde 24 ay distribütör garantisi.</p>
                </div>
            </div>
        </div>

        <!-- Price Alert Modal -->
        <div id="price-alert-modal" class="fixed inset-0 z-[100] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="bg-yellow-500 p-6 text-center relative">
                    <i class="fa-solid fa-bell text-4xl text-white mb-2"></i>
                    <h3 class="text-xl font-black text-afiDark">Fiyat Alarmı Kur</h3>
                    <p class="text-yellow-800 text-sm mt-1">Bu ürünün fiyatı düştüğünde size haber verelim.</p>
                    <button onclick="document.getElementById('price-alert-modal').classList.add('hidden')" class="absolute top-4 right-4 text-yellow-800 hover:text-afiDark">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form id="price-alert-form" onsubmit="submitPriceAlert(event)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Hedef Fiyat (₺)</label>
                            <div class="relative">
                                <input type="number" name="target_price" required min="1" step="0.01" class="w-full border border-gray-300 rounded-xl py-3 pl-4 pr-10 focus:ring-2 focus:ring-yellow-400 focus:outline-none font-bold text-lg" value="{{ $product->price }}">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₺</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Hangi fiyata düştüğünde haber verelim?</p>
                        </div>

                        @guest
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">E-Posta veya Telefon</label>
                            <input type="text" name="contact_info" required class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-yellow-400 focus:outline-none" placeholder="örn: info@afibilisim.com veya 5551234567">
                            <p class="text-xs text-gray-500 mt-1">Bildirimin gönderileceği iletişim adresi.</p>
                        </div>
                        @endguest

                        <button type="submit" id="price-alert-submit-btn" class="w-full bg-afiDark text-white font-bold py-3 rounded-xl hover:bg-yellow-500 hover:text-afiDark transition flex justify-center items-center gap-2">
                            <i class="fa-solid fa-check"></i> Alarmı Kaydet
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Description Section -->
    @if(isset($product) && !empty($product->description))
    <section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 pb-6 md:pb-16">
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-12">
            <h2 class="font-heading text-lg md:text-2xl font-black text-afiDark mb-4 md:mb-6 border-b border-gray-100 pb-3 md:pb-4 flex items-center gap-2.5">
                <i class="fa-solid fa-align-left text-yellow-500"></i> Ürün Açıklaması
            </h2>
            <div class="prose max-w-none text-gray-600 text-xs sm:text-sm md:text-base leading-relaxed whitespace-pre-line">
                {{ $product->description }}
            </div>
        </div>
    </section>
    @endif

    <!-- Technical Specifications Section -->
    @if(!empty($specs) || !empty($values))
    @php
        $keyMap = [
            'cpu'                 => 'İşlemci (CPU)',
            'processor'           => 'İşlemci (CPU)',
            'cpu_model'           => 'İşlemci Modeli',
            'ram'                 => 'RAM (Bellek)',
            'memory'              => 'RAM (Bellek)',
            'ram_type'            => 'RAM Tipi',
            'gpu'                 => 'Ekran Kartı (GPU)',
            'graphics'            => 'Ekran Kartı (GPU)',
            'graphics_card'       => 'Ekran Kartı (GPU)',
            'gpu_chipset'         => 'Ekran Kartı Çipi',
            'storage'             => 'Depolama (SSD / HDD)',
            'capacity'            => 'Kapasite',
            'read_speed'          => 'Okuma Hızı (MB/s)',
            'write_speed'         => 'Yazma Hızı (MB/s)',
            'rpm'                 => 'Dönüş Hızı (RPM)',
            'type'                => 'Tip / Tür',
            'motherboard_support' => 'Anakart Desteği',
            'radiator_support'    => 'Radyatör Desteği',
            'max_gpu_length'      => 'Maks. GPU Uzunluğu',
            'brand'               => 'Marka',
            'chipset'             => 'Çip Seti',
            'socket'              => 'Soket Tipi',
            'psu'                 => 'Güç Kaynağı (PSU)',
            'power_supply'        => 'Güç Kaynağı (PSU)',
            'wattage'             => 'Güç Değeri (Watt)',
            'screen_size'         => 'Ekran Boyutu',
            'refresh_rate'        => 'Yenileme Hızı (Hz)',
            'response_time'       => 'Tepki Süresi (ms)',
            'panel_type'          => 'Panel Tipi',
            'resolution'          => 'Çözünürlük',
            'interface'           => 'Bağlantı Arayüzü',
            'form_factor'         => 'Form Faktörü',
            'warranty'            => 'Garanti Süresi',
            'color'               => 'Renk',
            'weight'              => 'Ağırlık',
            'cooling'             => 'Soğutma Tipi',
            'fan_size'            => 'Fan Boyutu',
            'rgb'                 => 'RGB Aydınlatma',
            'is_gaming'           => 'Oyuncu Serisi',
            'os'                  => 'İşletim Sistemi',
            'battery'             => 'Batarya',
            'camera'              => 'Kamera',
            'lens'                => 'Lens',
            'sensor'              => 'Sensör'
        ];
    @endphp
    <section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 pb-6 md:pb-16">
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-12">
            <h2 class="font-heading text-lg md:text-2xl font-black text-afiDark mb-4 md:mb-8 border-b border-gray-100 pb-3 md:pb-4 flex items-center gap-2.5">
                <i class="fa-solid fa-list-check text-yellow-500"></i> Teknik Özellikler
            </h2>
            
            <div class="overflow-hidden rounded-xl md:rounded-2xl border border-gray-100">
                <table class="w-full text-left text-xs md:text-base text-gray-600">
                    <tbody class="divide-y divide-gray-100">
                        @if(!empty($specs))
                            @foreach($specs as $spec)
                                @php
                                    $rawName = $spec['name'] ?? 'Özellik';
                                    $kLower = strtolower($rawName);
                                    $specDisplayName = $keyMap[$kLower] ?? ucwords(str_replace(['_', '-'], ' ', $rawName));
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <th scope="row" class="px-3 py-2.5 md:px-6 md:py-4 font-bold text-afiDark bg-gray-50/50 w-2/5 md:w-1/4 border-r border-gray-100 text-xs md:text-sm">
                                        {{ $specDisplayName }}
                                    </th>
                                    <td class="px-3 py-2.5 md:px-6 md:py-4 text-gray-700 font-medium text-xs md:text-sm">
                                        @if(isset($values[$rawName]) && is_array($values[$rawName]))
                                            {{ implode(', ', $values[$rawName]) }}
                                        @elseif(isset($values[$rawName]) && !empty($values[$rawName]))
                                            {{ $values[$rawName] }}
                                        @elseif(isset($values[$kLower]) && is_array($values[$kLower]))
                                            {{ implode(', ', $values[$kLower]) }}
                                        @elseif(isset($values[$kLower]) && !empty($values[$kLower]))
                                            {{ $values[$kLower] }}
                                        @else
                                            <span class="text-gray-400 italic">Belirtilmemiş</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($values as $key => $val)
                                @php
                                    $kLower = strtolower($key);
                                    $specName = $keyMap[$kLower] ?? ucwords(str_replace(['_', '-'], ' ', $key));
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <th scope="row" class="px-3 py-2.5 md:px-6 md:py-4 font-bold text-afiDark bg-gray-50/50 w-2/5 md:w-1/4 border-r border-gray-100 text-xs md:text-sm">
                                        {{ $specName }}
                                    </th>
                                    <td class="px-3 py-2.5 md:px-6 md:py-4 text-gray-700 font-medium text-xs md:text-sm">
                                        {{ is_array($val) ? implode(', ', $val) : $val }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    <!-- Reviews Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 pb-12 md:pb-24">
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-12">
            <h2 class="font-heading text-lg md:text-2xl font-black text-afiDark mb-4 md:mb-8 border-b border-gray-100 pb-3 md:pb-4">Müşteri Yorumları</h2>
            
            <div class="flex flex-col md:flex-row gap-12">
                <!-- Rating Stats Bar -->
                <div class="md:w-1/3">
                    <div class="flex items-end gap-3 mb-4">
                        <span class="text-5xl font-black text-afiDark">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-gray-500 pb-1">/ 5</span>
                    </div>
                    <!-- Puan Barları (Görsel Statik Örnek) -->
                    <div class="space-y-2 mt-6">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold w-3 text-gray-400">5</span>
                            <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-yellow-400 h-full w-[70%] rounded-full"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold w-3 text-gray-400">4</span>
                            <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-yellow-400 h-full w-[20%] rounded-full"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold w-3 text-gray-400">3</span>
                            <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-yellow-400 h-full w-[5%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review List & Form -->
                <div class="md:w-2/3">
                    @if(isset($product) && $product->approvedReviews && $product->approvedReviews->count() > 0)
                        @foreach($product->approvedReviews as $review)
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="text-yellow-500 text-sm mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating) <i class="fa-solid fa-star"></i> @else <i class="fa-regular fa-star"></i> @endif
                                            @endfor
                                        </div>
                                        <span class="font-bold text-afiDark">{{ $review->user_name }}</span>
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded ml-2"><i class="fa-solid fa-circle-check"></i> Onaylı Alıcı</span>
                                    </div>
                                    <span class="text-sm text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-gray-600 text-sm">{{ $review->comment_text }}</p>
                            </div>
                        @endforeach
                    @else
                        <!-- Statik Demo Yorum -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="text-yellow-500 text-sm mb-1">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="font-bold text-afiDark">O*** K***</span>
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded ml-2"><i class="fa-solid fa-circle-check"></i> Onaylı Alıcı</span>
                                </div>
                                <span class="text-sm text-gray-400">12 Mayıs 2026</span>
                            </div>
                            <p class="text-gray-600 text-sm">Ürün gerçekten sıfırından farksız geldi. Paketlemesi çok özenliydi. Furmark testlerinde sıcaklık değerleri oldukça iyi. Afi Bilişim ekibine teşekkürler, 2. el alırken tereddütüm vardı ama çok memnun kaldım.</p>
                        </div>

                    @endif

                    <!-- Add Review Form -->
                    <div class="bg-afiGray p-6 rounded-2xl mt-8">
                        <h4 class="font-bold text-lg mb-4 text-afiDark">Değerlendirmeni Paylaş</h4>
                        
                        @if(session('success'))
                            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 text-sm font-medium">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('reviews.store', $product->id ?? 1) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Adınız Soyadınız</label>
                                    <input type="text" name="user_name" required class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none" placeholder="Adınız">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Puanınız</label>
                                    <select name="rating" required class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                                        <option value="5">5 - Mükemmel</option>
                                        <option value="4">4 - İyi</option>
                                        <option value="3">3 - Orta</option>
                                        <option value="2">2 - Kötü</option>
                                        <option value="1">1 - Çok Kötü</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Yorumunuz</label>
                                <textarea name="comment_text" required rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none" placeholder="Ürünle ilgili deneyimlerinizi paylaşın..."></textarea>
                            </div>
                            <button type="submit" class="bg-afiDark text-white font-bold py-3 px-6 rounded-xl text-sm hover:bg-yellow-500 hover:text-afiDark transition">
                                Yorumu Gönder
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cross-Selling (İlgili Ürünler) Section -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="max-w-7xl mx-auto px-6 md:px-12 pb-24">
        <h3 class="font-heading text-2xl font-black text-afiDark mb-8 border-b border-gray-100 pb-4">
            Bu ürünle birlikte bunları da alabilirsiniz
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full">
            @foreach($relatedProducts as $related)
                <div class="scroll-reveal-item w-full">
                    @include('products._demo_card', ['product' => $related])
                </div>
            @endforeach
        </div>
    </section>
    @endif

<script>
async function toggleFavorite(btn, productId) {
    try {
        const response = await fetch(`/favorites/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (response.status === 401) {
            alert('Favori işlemi için giriş yapmalısınız.');
            window.location.href = '/login';
            return;
        }
        
        const data = await response.json();
        const icon = btn.querySelector('i');
        const badge = document.getElementById('fav-badge');
        
        if(badge && data.favCount !== undefined) {
            badge.innerText = data.favCount;
            if(data.favCount > 0) {
                badge.classList.remove('hidden');
                badge.classList.add('scale-150');
                setTimeout(() => badge.classList.remove('scale-150'), 300);
            } else {
                badge.classList.add('hidden');
            }
        }
        
        if (data.status === 'added') {
            btn.classList.remove('bg-gray-100', 'text-gray-400');
            btn.classList.add('bg-red-50', 'text-red-500');
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
        } else {
            btn.classList.remove('bg-red-50', 'text-red-500');
            btn.classList.add('bg-gray-100', 'text-gray-400');
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
        }
    } catch (error) {
        console.error("Hata:", error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    if(addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const btnText = document.getElementById('cart-btn-text');
            
            btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Ekleniyor...';
            this.disabled = true;

            try {
                const response = await fetch(`/sepet/ekle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                console.log("Sunucu Yanıtı:", data);
                
                if (data.status === 'success') {
                    // Update Badge
                    const badge = document.getElementById('cart-badge');
                    if(badge) {
                        badge.innerText = data.cartCount;
                        badge.classList.remove('hidden');
                        
                        // Add a little pop animation to the badge
                        badge.classList.add('scale-150');
                        setTimeout(() => badge.classList.remove('scale-150'), 300);
                    }
                    
                    // Success state (Yeşil buton)
                    this.classList.remove('bg-gradient-btn', 'text-afiDark');
                    this.classList.add('!bg-green-500', '!text-white');
                    btnText.innerHTML = '<i class="fa-solid fa-check"></i> Sepete Eklendi!';
                    
                    setTimeout(() => {
                        this.classList.add('bg-gradient-btn', 'text-afiDark');
                        this.classList.remove('!bg-green-500', '!text-white');
                        btnText.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Sepete Ekle';
                        this.disabled = false;
                    }, 2000);
                }
            } catch (error) {
                console.error("Hata Detayı:", error);
                btnText.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Hata Oluştu';
                setTimeout(() => {
                    btnText.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Sepete Ekle';
                    this.disabled = false;
                }, 2000);
            }
        });
    }
});

async function submitPriceAlert(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('price-alert-submit-btn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Kaydediliyor...';
    btn.disabled = true;

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('{{ route('price-alert.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': data._token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert(result.message);
            document.getElementById('price-alert-modal').classList.add('hidden');
        } else {
            alert(result.message || 'Bir hata oluştu.');
        }
    } catch (error) {
        alert('Sunucu ile iletişim kurulamadı.');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
</script>
@endsection
