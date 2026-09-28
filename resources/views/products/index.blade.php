@extends('layouts.app')

@section('title', (isset($activeCategory) ? $activeCategory->name . ' Fiyatları ve Modelleri' : 'Tüm Ürünler') . ' | Afi Bilişim')
@section('meta_description', 'Afi Bilişim ' . (isset($activeCategory) ? $activeCategory->name : 'teknoloji') . ' kataloğu. En yeni sıfır ve test edilmiş ikinci el modelleri uygun fiyat ve hızlı kargo ile inceleyin.')
@section('canonical', request()->url())
@section('og_title', (isset($activeCategory) ? $activeCategory->name : 'Tüm Ürünler') . ' | Afi Bilişim')
@section('og_description', 'Test edilmiş ikinci el ve sıfır donanımlar, laptoplar, masaüstü bilgisayarlar ve güvenlik sistemleri.')

@section('content')
<style>
    /* ---------- Product Grid & Responsive Card Layout ---------- */
    #product-grid {
        display: grid !important;
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        gap: 1rem !important;
        width: 100% !important;
        box-sizing: border-box;
    }

    @media (min-width: 640px) {
        #product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
    }

    @media (min-width: 1024px) {
        #product-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            gap: 1.5rem !important;
        }
    }

    .product-card,
    .flip-card {
        position: relative;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        flex-grow: 0 !important;
        flex-shrink: 0 !important;
        height: 420px !important;
        min-height: 420px !important;
        max-height: 420px !important;
        perspective: 1000px;
        box-sizing: border-box;
    }

    .scroll-reveal-item {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        flex-grow: 0 !important;
        flex-shrink: 0 !important;
        height: 100% !important;
        display: block !important;
        box-sizing: border-box;
    }

    /* ---------- Flip Card ---------- */
    .product-card-hover:hover .product-img { transform: scale(1.05); }
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
    .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
    .flip-card.flipped .flip-inner { transform: rotateY(180deg); }

    /* ---------- Hiyerarşik Kategori Accordion & Interactive Buttons ---------- */
    .cat-children {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1);
    }
    .cat-item.open > .cat-children { max-height: 600px; }
    .cat-toggle-icon { transition: transform 0.3s ease; }
    .cat-item.open > .cat-header .cat-toggle-icon { transform: rotate(90deg); }

    /* Interactive Sidebar Button Styling */
    .sidebar-cat-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .sidebar-cat-btn:hover {
        transform: translateX(6px);
        background-color: #fefce8; /* yellow-50 */
        color: #854d0e; /* yellow-800 */
    }
    .sidebar-cat-btn:hover .cat-icon-box {
        background-color: #eab308; /* yellow-500 */
        color: #ffffff;
        transform: scale(1.1);
    }
    .sidebar-cat-btn.active-cat {
        background: linear-gradient(135deg, #fef08a 0%, #fde047 100%);
        color: #0f172a;
        font-weight: 800;
        border-left: 4px solid #ca8a04;
        box-shadow: 0 4px 12px rgba(234, 179, 8, 0.25);
    }
    .sidebar-cat-btn.active-cat .cat-icon-box {
        background-color: #0f172a;
        color: #facc15;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .cat-icon-box {
        transition: all 0.3s ease;
    }

    .sub-cat-link {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sub-cat-link:hover {
        transform: translateX(5px);
        background-color: #fefce8;
        color: #854d0e;
    }
    .sub-cat-link.active-sub {
        background-color: #fef08a;
        color: #0f172a;
        font-weight: 800;
        border-left: 3px solid #ca8a04;
        box-shadow: 0 2px 8px rgba(234, 179, 8, 0.15);
    }

    /* ---------- Spec Filtre Accordion ---------- */
    .spec-group-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .spec-group.open > .spec-group-body {
        max-height: 800px;
    }
    .spec-toggle-icon { transition: transform 0.3s ease; }
    .spec-group.open .spec-toggle-icon { transform: rotate(180deg); }
    .spec-group > button { cursor: pointer; }

    /* ---------- Custom Checkbox ---------- */
    .spec-checkbox { display: none; }
    .spec-checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 12px;
        color: #4B5563;
        transition: all 0.15s;
        user-select: none;
    }
    .spec-checkbox-label:hover { background: #FEF9C3; color: #854D0E; }
    .spec-checkbox-box {
        width: 16px; height: 16px;
        border: 2px solid #D1D5DB;
        border-radius: 4px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }
    .spec-checkbox:checked + .spec-checkbox-label .spec-checkbox-box {
        background: #EAB308;
        border-color: #EAB308;
    }
    .spec-checkbox:checked + .spec-checkbox-label .spec-checkbox-box::after {
        content: '';
        display: block;
        width: 4px; height: 8px;
        border: 2px solid white;
        border-top: none; border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
    }
    .spec-checkbox:checked + .spec-checkbox-label {
        color: #854D0E;
        background: #FEF9C3;
        font-weight: 600;
    }

    /* ---------- Price Range Slider ---------- */
    .price-slider {
        -webkit-appearance: none;
        width: 100%; height: 4px;
        background: #E5E7EB;
        border-radius: 2px;
        outline: none;
    }
    .price-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #EAB308;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    /* ---------- Aktif Filtre Badge ---------- */
    .active-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #FEF9C3;
        color: #854D0E;
        border: 1px solid #FDE047;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    /* ---------- Scroll Reveal Animations ---------- */
    .scroll-reveal-item {
        opacity: 0;
        transform: translateY(35px) scale(0.97);
        transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .scroll-reveal-item.is-revealed {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
</style>

@php
    $specKeyTranslations = [
        'cpu'         => 'İşlemci (CPU)',
        'ram'         => 'RAM (Bellek)',
        'gpu'         => 'Ekran Kartı (GPU)',
        'storage'     => 'Depolama (SSD / HDD)',
        'is_gaming'   => 'Oyuncu Bilgisayarı',
        'cpu_model'   => 'İşlemci Modeli',
        'ram_type'    => 'Bellek Tipi (RAM)',
        'socket'      => 'Soket Tipi',
        'gpu_chipset' => 'Ekran Kartı Çipi',
        'screen_size' => 'Ekran Boyutu',
        'resolution'  => 'Çözünürlük',
        'brand'       => 'Marka',
    ];
@endphp

<div class="bg-afiGray py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

        {{-- Header + Aktif Filtreler --}}
        <div class="mb-8">
            <h1 class="text-4xl font-black text-afiDark">
                {{ isset($activeCategory) ? $activeCategory->name : 'Ürünlerimiz' }}
            </h1>
            <p class="text-gray-500 mt-2">Aradığınız en iyi teknoloji çözümleri tek bir yerde.</p>

            {{-- Aktif spec ve oyuncu kasası filtrelerini göster --}}
            @if(!empty($specFilters) || request('is_gaming') || request('gaming_only'))
                <div class="flex flex-wrap gap-2 mt-4">
                    @if(request('is_gaming') || request('gaming_only'))
                        <span class="active-filter-badge !bg-purple-100 !text-purple-900 !border-purple-300">
                            <i class="fa-solid fa-gamepad text-purple-600 text-[10px]"></i>
                            Oyuncu Kasaları (Gaming PC)
                            <a href="{{ request()->fullUrlWithQuery(['is_gaming' => null, 'gaming_only' => null]) }}"
                               class="ml-1 hover:text-red-600 transition" title="Kaldır">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </span>
                    @endif
                    @foreach($specFilters as $key => $vals)
                        @foreach((array)$vals as $val)
                            @php
                                $displayKeyName = $specKeyTranslations[strtolower($key)] ?? $key;
                            @endphp
                            <span class="active-filter-badge">
                                <i class="fa-solid fa-tag text-[9px]"></i>
                                {{ $displayKeyName }}: {{ $val }}
                                <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except("specs[{$key}][]"), [])) }}"
                                   class="ml-1 hover:text-red-600 transition" title="Kaldır">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </span>
                        @endforeach
                    @endforeach
                    <a href="{{ route('products.index') }}{{ request('category_id') ? '?category_id='.request('category_id') : '' }}"
                       class="active-filter-badge !bg-red-50 !text-red-600 !border-red-200 hover:!bg-red-100 transition">
                        <i class="fa-solid fa-xmark"></i> Tümünü Temizle
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 w-full">

            {{-- ============================================================
                 SIDEBAR
            ============================================================ --}}
            <aside class="lg:col-span-1 w-full">
                <form id="filter-form" method="GET" action="{{ url()->current() }}">
                    {{-- Gizli alanlar: kategori ve durum filtrelerini koru --}}
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 sticky top-28 space-y-6">

                        {{-- Başlık + Temizle --}}
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-bold text-afiDark">
                                <i class="fa-solid fa-sliders text-yellow-500 mr-1.5"></i> Filtreler
                            </h2>
                            <a href="{{ route('products.index') }}"
                               class="text-[11px] font-bold text-gray-400 hover:text-red-500 transition">
                                SIFIRLA
                            </a>
                        </div>

                        {{-- ─── KATEGORİLER ─── --}}
                        <div>
                            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                <i class="fa-solid fa-sitemap text-yellow-500 mr-1"></i> Kategoriler
                            </h3>
                            @php
                                $currentCatId = request('category_id', isset($activeCategory) ? $activeCategory->id : null);
                                $isAllProductsActive = empty($currentCatId);
                            @endphp
                            <ul class="space-y-1.5" id="sidebar-cat-list">

                                {{-- Tüm Ürünler --}}
                                <li>
                                    <a href="{{ route('products.index') }}"
                                       class="sidebar-cat-btn flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold
                                              {{ $isAllProductsActive
                                                  ? 'active-cat'
                                                  : 'text-gray-600 hover:text-afiDark' }}">
                                        <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                                                     {{ $isAllProductsActive
                                                         ? 'bg-afiDark text-yellow-400'
                                                         : 'bg-gray-100 text-gray-500' }}">
                                            <i class="fa-solid fa-layer-group text-xs"></i>
                                        </span>
                                        Tüm Ürünler
                                    </a>
                                </li>

                                {{-- Ana Kategoriler + Accordion Alt Kategoriler --}}
                                @foreach($categories as $mainCat)
                                    @php
                                        $hasChildren   = $mainCat->children->isNotEmpty();
                                        $isMainActive  = $currentCatId == $mainCat->id;
                                        $isChildActive = $hasChildren && $mainCat->children->contains('id', $currentCatId);
                                        $isOpen        = $isMainActive || $isChildActive;
                                    @endphp
                                    <li class="cat-item {{ $isOpen ? 'open' : '' }}" data-cat="{{ $mainCat->id }}">
                                        <div class="cat-header sidebar-cat-btn flex items-center gap-2.5 px-3 py-2.5 rounded-xl cursor-pointer
                                                    {{ $isMainActive || $isChildActive
                                                        ? 'active-cat'
                                                        : 'text-gray-600 hover:text-afiDark' }}"
                                             @if($hasChildren)
                                                 onclick="toggleCat(this.closest('.cat-item'))"
                                             @else
                                                 onclick="window.location='{{ route('category.show', $mainCat->slug) }}'"
                                             @endif>
                                            <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                                                         {{ $isMainActive || $isChildActive ? 'bg-afiDark text-yellow-400' : 'bg-gray-100 text-gray-500' }}">
                                                <i class="fa-solid {{ $mainCat->icon ?? 'fa-folder' }} text-xs"></i>
                                            </span>
                                            <span class="flex-1 text-sm font-semibold leading-tight">{{ $mainCat->name }}</span>
                                            @if($hasChildren)
                                                <i class="fa-solid fa-chevron-right text-[10px] cat-toggle-icon ml-auto {{ $isMainActive || $isChildActive ? 'text-afiDark' : 'text-gray-400' }}"></i>
                                            @endif
                                        </div>

                                        @if($hasChildren)
                                            <div class="cat-children">
                                                <ul class="mt-1.5 ml-4 pl-3 border-l-2 border-amber-200 space-y-1 pb-1">
                                                    <li>
                                                        <a href="{{ route('category.show', $mainCat->slug) }}"
                                                           class="sub-cat-link flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium
                                                                  {{ $isMainActive ? 'active-sub' : 'text-gray-500 hover:text-afiDark' }}">
                                                            <i class="fa-solid fa-circle-dot text-[8px] {{ $isMainActive ? 'text-amber-600' : 'text-gray-300' }}"></i>
                                                            Tümü
                                                        </a>
                                                    </li>
                                                    @foreach($mainCat->children as $subCat)
                                                        @php $isSubActive = $currentCatId == $subCat->id; @endphp
                                                        <li>
                                                            <a href="{{ route('category.show', $subCat->slug) }}"
                                                               class="sub-cat-link flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium
                                                                      {{ $isSubActive ? 'active-sub' : 'text-gray-500 hover:text-afiDark' }}">
                                                                <i class="fa-solid fa-circle-dot text-[8px] {{ $isSubActive ? 'text-amber-600' : 'text-gray-300' }}"></i>
                                                                {{ $subCat->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        {{-- ─── OYUNCU KASALARI (GAMING PC) ÖZEL FİLTRE ─── --}}
                        <div>
                            <div class="h-px bg-gray-100 mb-5"></div>
                            <div class="bg-gradient-to-br from-gray-900 via-purple-950 to-indigo-950 rounded-2xl p-4 shadow-md border border-purple-500/30 relative overflow-hidden text-white">
                                <div class="absolute -right-3 -bottom-3 w-16 h-16 bg-purple-500/20 rounded-full blur-xl pointer-events-none"></div>
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xs font-black text-purple-300 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-gamepad text-cyan-400 text-sm animate-pulse"></i> Oyuncu Kasaları
                                    </h3>
                                    <span class="text-[9px] bg-purple-500/30 text-purple-200 border border-purple-400/40 px-2 py-0.5 rounded-full font-bold">
                                        GAMING PC
                                    </span>
                                </div>
                                <label class="flex items-center gap-2.5 cursor-pointer select-none group mt-3">
                                    <div class="relative flex items-center">
                                        <input type="checkbox"
                                               name="is_gaming"
                                               value="1"
                                               {{ request('is_gaming') || request('gaming_only') ? 'checked' : '' }}
                                               onchange="document.getElementById('filter-form').submit()"
                                               class="w-4 h-4 rounded border-purple-400 text-purple-600 focus:ring-purple-500 bg-gray-900 cursor-pointer">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs font-bold text-white group-hover:text-cyan-300 transition-colors">Sadece Oyuncu Kasaları</span>
                                        <p class="text-[10px] text-purple-300/80 leading-tight">RGB & Yüksek Performanslı PC</p>
                                    </div>
                                    @if(isset($gamingCount) && $gamingCount > 0)
                                        <span class="text-[10px] bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 px-2 py-0.5 rounded-full font-black">
                                            {{ $gamingCount }}
                                        </span>
                                    @endif
                                </label>
                            </div>
                        </div>

                        {{-- ─── MARKA FİLTRESİ ─── --}}
                        @if(isset($allBrands) && $allBrands->count() > 0)
                        <div>
                            <div class="h-px bg-gray-100 mb-5"></div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                                    <i class="fa-solid fa-award text-yellow-500"></i> Markalar
                                </h3>
                                @if(request('brand'))
                                    <a href="{{ request()->fullUrlWithoutQuery('brand') }}" class="text-[10px] text-red-500 font-bold hover:underline">Temizle</a>
                                @endif
                            </div>
                            <div class="space-y-1 max-h-48 overflow-y-auto pr-1">
                                @foreach($allBrands as $bName)
                                    @php $isBrandActive = request('brand') === $bName; @endphp
                                    <label class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition select-none {{ $isBrandActive ? 'bg-yellow-500/10 text-yellow-800 font-bold border border-yellow-500/30' : 'text-gray-600 hover:bg-gray-100' }}">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" 
                                                   name="brand" 
                                                   value="{{ $bName }}" 
                                                   {{ $isBrandActive ? 'checked' : '' }} 
                                                   onchange="document.getElementById('filter-form').submit()" 
                                                   class="text-yellow-500 focus:ring-yellow-400">
                                            <span>{{ $bName }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- ─── FİYAT ARALIĞI ─── --}}
                        @if(isset($priceRange) && $priceRange['max'] > 0)
                            <div>
                                <div class="h-px bg-gray-100 mb-5"></div>
                                <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                                    <i class="fa-solid fa-turkish-lira-sign text-yellow-500 mr-1"></i> Fiyat Aralığı
                                </h3>
                                <div class="px-1 space-y-3">
                                    <input
                                        type="range"
                                        name="price_max"
                                        id="price-slider"
                                        class="price-slider"
                                        min="{{ $priceRange['min'] }}"
                                        max="{{ $priceRange['max'] }}"
                                        value="{{ request('price_max', $priceRange['max']) }}"
                                        oninput="document.getElementById('price-max-display').textContent = parseInt(this.value).toLocaleString('tr-TR') + ' ₺'"
                                    >
                                    <div class="flex justify-between text-xs text-gray-500 font-medium">
                                        <span>{{ number_format($priceRange['min'], 0, ',', '.') }} ₺</span>
                                        <span id="price-max-display" class="text-yellow-700 font-bold">
                                            {{ number_format(request('price_max', $priceRange['max']), 0, ',', '.') }} ₺
                                        </span>
                                    </div>
                                    <button type="submit"
                                            class="w-full py-2 bg-yellow-500 text-afiDark text-xs font-bold rounded-lg hover:bg-yellow-400 transition">
                                        Fiyat Filtrele
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- ─── ÖZELLİK FİLTRELERİ (Dinamik, kategoriye göre) ─── --}}
                        @if($specFilterOptions->isNotEmpty())
                            @php
                                $specKeyTranslations = [
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
                                ];
                            @endphp
                            <div>
                                <div class="h-px bg-gray-100 mb-5"></div>
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                        <i class="fa-solid fa-microchip text-yellow-500 mr-1"></i> Özellikler
                                    </h3>
                                    <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-bold">
                                        {{ $activeCategory->name ?? '' }}
                                    </span>
                                </div>

                                <div class="space-y-3" id="spec-filters">
                                    @foreach($specFilterOptions as $specKey => $specValues)
                                        @php
                                            $keyLower         = strtolower($specKey);
                                            $displaySpecKey   = $specKeyTranslations[$keyLower] ?? ucwords(str_replace(['_', '-'], ' ', $specKey));
                                            $groupId          = 'spec-group-' . \Illuminate\Support\Str::slug($specKey);
                                            $currentVals      = (array) ($specFilters[$specKey] ?? []);
                                            $hasActiveInGroup = !empty(array_intersect($currentVals, $specValues->toArray()));
                                        @endphp

                                        <div class="spec-group {{ $hasActiveInGroup ? 'open' : '' }}" id="{{ $groupId }}">
                                            {{-- Grup Başlığı --}}
                                            <button type="button"
                                                    onclick="toggleSpecGroup('{{ $groupId }}')"
                                                    data-target="{{ $groupId }}"
                                                    class="spec-accordion-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl
                                                           {{ $hasActiveInGroup ? 'bg-yellow-50 text-yellow-800' : 'bg-gray-50 text-gray-700' }}
                                                           hover:bg-yellow-50 transition-all text-sm font-semibold">
                                                <span class="flex items-center gap-2">
                                                    @if($hasActiveInGroup)
                                                        <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                                    @endif
                                                    {{ $displaySpecKey }}
                                                    @if($hasActiveInGroup)
                                                        <span class="text-[10px] bg-yellow-500 text-white px-1.5 py-0.5 rounded-full">{{ count($currentVals) }}</span>
                                                    @endif
                                                </span>
                                                <i class="fa-solid fa-chevron-down text-[10px] spec-toggle-icon text-gray-400"></i>
                                            </button>

                                            {{-- Seçenekler --}}
                                            <div class="spec-group-body">
                                                <div class="pt-1 pb-2">
                                                    @foreach($specValues as $val)
                                                        @php $isChecked = in_array($val, $currentVals); @endphp
                                                        <div>
                                                            <input
                                                                type="checkbox"
                                                                class="spec-checkbox"
                                                                id="spec-{{ \Illuminate\Support\Str::slug($specKey) }}-{{ \Illuminate\Support\Str::slug($val) }}"
                                                                name="specs[{{ $specKey }}][]"
                                                                value="{{ $val }}"
                                                                {{ $isChecked ? 'checked' : '' }}
                                                                onchange="document.getElementById('filter-form').submit()"
                                                            >
                                                            <label for="spec-{{ \Illuminate\Support\Str::slug($specKey) }}-{{ \Illuminate\Support\Str::slug($val) }}"
                                                                   class="spec-checkbox-label">
                                                                <span class="spec-checkbox-box"></span>
                                                                <span class="truncate" title="{{ $val }}">{{ $val }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Filtrelenebilir spec bulunamadı durumu --}}
                            <div class="text-center py-4">
                                <p class="text-xs text-gray-400">Genel özellik filtresi bulunmuyor.</p>
                            </div>
                        @endif

                    </div>
                </form>
            </aside>

            {{-- ============================================================
                 ÜRÜN GRID
            ============================================================ --}}
            <main class="lg:col-span-3 min-w-0 w-full">

                {{-- Sonuç Özeti + Sıralama --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 bg-white/80 backdrop-blur-md rounded-2xl px-5 py-3.5 border border-gray-200/80 shadow-sm">
                    <p class="text-xs sm:text-sm text-gray-600 font-medium">
                        Toplam <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-lg border border-gray-200">{{ $products->total() }}</span> ürün bulundu
                        @if(!empty($specFilters) || request()->filled('price_min') || request()->filled('price_max') || request()->filled('search'))
                            <span class="inline-flex items-center gap-1 bg-yellow-500/10 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full ml-1 border border-yellow-500/20">
                                <i class="fa-solid fa-filter text-[10px]"></i> Filtrelendi
                            </span>
                        @endif
                    </p>

                    <div class="flex items-center gap-2">
                        <label for="product-sort-select" class="text-xs font-bold text-gray-500 whitespace-nowrap flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-down-wide-short text-yellow-500"></i> Sırala:
                        </label>
                        <select id="product-sort-select" 
                                onchange="applySort(this.value)" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 outline-none transition cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' || request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Yeni Eklenenler</option>
                            <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>En Çok Satanlar</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>En Düşük Fiyat</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>En Yüksek Fiyat</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6 w-full" id="product-grid">
                    @forelse($products as $index => $product)
                        <div class="scroll-reveal-item w-full">
                            @include('products._demo_card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 xl:col-span-3">
                            <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-gray-100 shadow-sm max-w-2xl mx-auto my-4">
                                <div class="w-16 h-16 bg-amber-50 text-amber-500 border border-amber-200 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-inner">
                                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                                </div>
                                
                                <h3 class="text-xl font-extrabold text-afiDark mb-2">Aradığınız kriterlere uygun ürün bulunamadı</h3>
                                
                                @if(request('search'))
                                    <p class="text-sm text-gray-500 mb-6">
                                        <span class="font-bold text-gray-700">"{{ request('search') }}"</span> terimiyle eşleşen sonuç bulunamadı. Lütfen kelimeyi kontrol edip tekrar deneyin veya popüler aramaları keşfedin.
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 mb-6">Seçtiğiniz kategori ve filtre kombinasyonuna uygun ürün stoklarımızda bulunmamaktadır.</p>
                                @endif

                                <!-- Popüler Hızlı Aramalar -->
                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-6">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">
                                        <i class="fa-solid fa-fire text-yellow-500 mr-1"></i> Popüler Arama Etiketleri
                                    </span>
                                    <div class="flex flex-wrap items-center justify-center gap-2">
                                        <a href="{{ route('products.index') }}?search=İşlemci" class="px-3 py-1.5 rounded-xl bg-white hover:bg-yellow-500 hover:text-afiDark text-gray-700 text-xs font-bold transition border border-gray-200 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-microchip text-yellow-600"></i> İşlemciler
                                        </a>
                                        <a href="{{ route('products.index') }}?search=Ekran+Kartı" class="px-3 py-1.5 rounded-xl bg-white hover:bg-yellow-500 hover:text-afiDark text-gray-700 text-xs font-bold transition border border-gray-200 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-desktop text-yellow-600"></i> Ekran Kartları
                                        </a>
                                        <a href="{{ route('products.index') }}?search=SSD" class="px-3 py-1.5 rounded-xl bg-white hover:bg-yellow-500 hover:text-afiDark text-gray-700 text-xs font-bold transition border border-gray-200 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-hard-drive text-yellow-600"></i> SSD'ler
                                        </a>
                                        <a href="{{ route('products.index') }}?search=Gaming+Laptop" class="px-3 py-1.5 rounded-xl bg-white hover:bg-yellow-500 hover:text-afiDark text-gray-700 text-xs font-bold transition border border-gray-200 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-laptop text-yellow-600"></i> Gaming Laptop
                                        </a>
                                        <a href="{{ route('second-hand.index') }}" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-500 hover:text-white text-xs font-bold transition border border-emerald-200 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-recycle"></i> İkinci El
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('products.index') }}"
                                       class="inline-flex items-center gap-2 bg-afiDark text-yellow-400 hover:bg-gray-800 font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-md">
                                        <i class="fa-solid fa-rotate-left"></i> Aramayı & Filtreleri Temizle
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </main>
        </div>
    </div>
</div>

<script>
/* ── Kategori Accordion ── */
window.toggleCat = function(liEl) {
    if (liEl) liEl.classList.toggle('open');
};

/* ── Spec Accordion ── */
window.toggleSpecGroup = function(groupId) {
    var groupEl = typeof groupId === 'string' ? document.getElementById(groupId) : groupId;
    if (groupEl) {
        groupEl.classList.toggle('open');
    }
};

/* ── Favori Toggle ── */
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
        const icon  = btn.querySelector('i');
        const badge = document.getElementById('fav-badge');

        if (badge && data.favCount !== undefined) {
            badge.innerText = data.favCount;
            if (data.favCount > 0) {
                badge.classList.remove('hidden');
                badge.classList.add('scale-150');
                setTimeout(() => badge.classList.remove('scale-150'), 300);
            } else {
                badge.classList.add('hidden');
            }
        }

        if (data.status === 'added') {
            btn.classList.replace('text-gray-400', 'text-red-500');
            icon.classList.replace('fa-regular', 'fa-solid');
        } else {
            btn.classList.replace('text-red-500', 'text-gray-400');
            icon.classList.replace('fa-solid', 'fa-regular');
        }
    } catch (error) {
        console.error('Hata:', error);
    }
}

/* ── Sıralama Değişimi ── */
function applySort(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', val);
    url.searchParams.set('page', '1');
    try { sessionStorage.setItem('products_scroll_pos', window.scrollY); } catch(e){}
    window.location.href = url.toString();
}

/* ── DOM Ready & Scroll Restoration ── */
document.addEventListener('DOMContentLoaded', function() {
    /* -- Scroll Restoration -- */
    try {
        const savedPos = sessionStorage.getItem('products_scroll_pos');
        if (savedPos !== null) {
            sessionStorage.removeItem('products_scroll_pos');
            window.scrollTo({
                top: parseInt(savedPos, 10),
                behavior: 'instant'
            });
        } else {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('category_id') || urlParams.has('specs') || urlParams.has('price_max') || urlParams.has('is_gaming') || urlParams.has('page')) {
                const target = document.getElementById('product-grid') || document.getElementById('filter-form');
                if (target) {
                    setTimeout(() => {
                        const topOffset = target.getBoundingClientRect().top + window.scrollY - 100;
                        window.scrollTo({ top: topOffset, behavior: 'smooth' });
                    }, 80);
                }
            }
        }
    } catch (e) {}

    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('change', function() {
            try { sessionStorage.setItem('products_scroll_pos', window.scrollY); } catch(e){}
        });
        filterForm.addEventListener('submit', function() {
            try { sessionStorage.setItem('products_scroll_pos', window.scrollY); } catch(e){}
        });
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && (link.classList.contains('sidebar-cat-btn') || link.classList.contains('sub-cat-link') || link.closest('.pagination, nav[aria-label="Pagination"]'))) {
            try { sessionStorage.setItem('products_scroll_pos', window.scrollY); } catch(e){}
        }
    });

    /* -- Scroll Reveal: Immediate fallback for product grid cards -- */
    var revealItems = document.querySelectorAll('.scroll-reveal-item');
    revealItems.forEach(function(el, idx) {
        setTimeout(function() {
            el.classList.add('is-revealed', 'revealed');
        }, idx * 60);
    });
});
</script>
@endsection

