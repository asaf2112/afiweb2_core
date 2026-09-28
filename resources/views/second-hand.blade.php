@extends('layouts.app')

@section('title', 'İkinci El Güvence Deposu | Afi Bilişim')

@php
    $shSpecTranslations = [
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

@section('content')
<style>
    /* Flip Card */
    .flip-card.flipped .flip-inner { transform: rotateY(180deg); }
    .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }

    /* Hero gradient */
    .sh-hero-bg {
        background: linear-gradient(135deg, #0f1923 0%, #0d2818 50%, #111827 100%);
    }
    .sh-glow {
        background: radial-gradient(ellipse 60% 40% at 50% 0%, rgba(16,185,129,0.18) 0%, transparent 70%);
    }

    /* Kategori accordion */
    .cat-children { max-height:0; overflow:hidden; transition:max-height .35s cubic-bezier(.4,0,.2,1); }
    .cat-item.open > .cat-children { max-height:500px; }
    .cat-toggle-icon { transition:transform .3s ease; }
    .cat-item.open > .cat-header .cat-toggle-icon { transform:rotate(90deg); }

    /* Interactive Sidebar Button Styling */
    .sidebar-cat-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .sidebar-cat-btn:hover {
        transform: translateX(6px);
        background-color: #ecfdf5; /* emerald-50 */
        color: #065f46; /* emerald-800 */
    }
    .sidebar-cat-btn:hover .cat-icon-box {
        background-color: #10b981; /* emerald-500 */
        color: #ffffff;
        transform: scale(1.1);
    }
    .sidebar-cat-btn.active-cat {
        background: linear-gradient(135deg, #a7f3d0 0%, #6ee7b7 100%);
        color: #064e3b;
        font-weight: 800;
        border-left: 4px solid #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
    .sidebar-cat-btn.active-cat .cat-icon-box {
        background-color: #064e3b;
        color: #6ee7b7;
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
        background-color: #ecfdf5;
        color: #065f46;
    }
    .sub-cat-link.active-sub {
        background-color: #a7f3d0;
        color: #064e3b;
        font-weight: 800;
        border-left: 3px solid #059669;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }

    /* Spec accordion */
    .spec-group-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .spec-group.open > .spec-group-body {
        max-height: 800px;
    }
    .spec-toggle-icon { transition: transform 0.25s ease; }
    .spec-group.open .spec-toggle-icon { transform: rotate(180deg); }
    .spec-group > button { cursor: pointer; }

    /* Custom checkbox */
    .spec-checkbox { display:none; }
    .spec-checkbox-label {
        display:flex; align-items:center; gap:8px;
        padding:6px 10px; border-radius:8px;
        cursor:pointer; font-size:12px; color:#4B5563;
        transition:all .15s; user-select:none;
    }
    .spec-checkbox-label:hover { background:#D1FAE5; color:#065F46; }
    .spec-checkbox-box {
        width:16px; height:16px; border:2px solid #D1D5DB;
        border-radius:4px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        transition:all .15s;
    }
    .spec-checkbox:checked + .spec-checkbox-label .spec-checkbox-box {
        background:#10B981; border-color:#10B981;
    }
    .spec-checkbox:checked + .spec-checkbox-label .spec-checkbox-box::after {
        content:''; display:block;
        width:4px; height:8px;
        border:2px solid white; border-top:none; border-left:none;
        transform:rotate(45deg) translate(-1px,-1px);
    }
    .spec-checkbox:checked + .spec-checkbox-label { color:#065F46; background:#D1FAE5; font-weight:600; }

    /* Price slider */
    .price-slider { -webkit-appearance:none; width:100%; height:4px; background:#E5E7EB; border-radius:2px; outline:none; }
    .price-slider::-webkit-slider-thumb {
        -webkit-appearance:none; width:16px; height:16px;
        border-radius:50%; background:#10B981; cursor:pointer;
        border:2px solid white; box-shadow:0 1px 4px rgba(0,0,0,.2);
    }

    /* Trust badge */
    .trust-badge {
        display:inline-flex; align-items:center; gap:6px;
        padding:6px 14px; border-radius:999px;
        font-size:12px; font-weight:700;
    }
    /* Stat card pulse */
    @keyframes sh-pulse { 0%,100%{ box-shadow:0 0 0 0 rgba(16,185,129,.4); } 50%{ box-shadow:0 0 0 8px rgba(16,185,129,0); } }
    .sh-stat-pulse { animation:sh-pulse 2.5s infinite; }
</style>

{{-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ --}}
<header class="sh-hero-bg relative overflow-hidden">
    <div class="sh-glow absolute inset-0 pointer-events-none"></div>

    {{-- Dekoratif daireler --}}
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
    <div class="absolute -left-16 bottom-0 w-64 h-64 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-20 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
            <div>
                {{-- Güvence badge --}}
                <span class="trust-badge bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-4 inline-flex">
                    <i class="fa-solid fa-shield-check"></i> Afi Bilişim Güvenceli
                </span>

                <h1 class="text-4xl md:text-5xl font-black text-white leading-tight mb-3">
                    İkinci El<br>
                    <span style="background:linear-gradient(90deg,#34d399,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        Güvence Deposu
                    </span>
                </h1>
                <p class="text-gray-400 text-lg max-w-xl">
                    Her ürün uzman teknisyenlerimiz tarafından 32 noktada test edilmiş, termal bakımı yapılmış ve 1 ay firma garantisiyle sunulmaktadır.
                    Olası donanım arızalarında birebir değişim imkanı.
                </p>

                {{-- Trust badges --}}
                <div class="flex flex-wrap gap-3 mt-6">
                    @foreach([
                        ['fa-circle-check','32 Nokta Test Edilmiş'],
                        ['fa-shield-check','1 Ay Firma Garantisi'],
                        ['fa-repeat','Arızada Birebir Değişim'],
                        ['fa-truck-fast','Güvenli Paketleme'],
                    ] as [$icon, $label])
                    <span class="trust-badge bg-white/5 text-gray-300 border border-white/10">
                        <i class="fa-solid {{ $icon }} text-emerald-400"></i> {{ $label }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- İstatistik kutusu --}}
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-6 min-w-[220px] text-center shrink-0 sh-stat-pulse">
                <div class="text-5xl font-black text-emerald-400 mb-1">{{ $totalUsed }}</div>
                <p class="text-gray-400 text-sm font-medium">İkinci El Ürün</p>
                <div class="mt-4 h-px bg-white/10 mb-4"></div>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div>
                        <div class="text-xl font-black text-white">{{ $products->total() }}</div>
                        <div class="text-[11px] text-gray-500">Sonuç</div>
                    </div>
                    <div>
                        <div class="text-xl font-black text-emerald-400">✓</div>
                        <div class="text-[11px] text-gray-500">1 Ay Garantili</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════
     İÇERİK: Sidebar + Grid + Güvence Bölümleri
═══════════════════════════════════════════ --}}
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

        {{-- ── HIZLI KATEGORİ VE FİLTRE HAPLARI ── --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none no-scrollbar text-xs font-semibold">
                <a href="{{ route('second-hand.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ empty(request()->all()) ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-white text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-layer-group"></i> Tüm İkinci El
                </a>
                <a href="{{ route('second-hand.index', ['is_gaming' => 1]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ request('is_gaming') == '1' ? 'bg-purple-600 text-white shadow-purple-500/20' : 'bg-white text-gray-700 hover:bg-purple-50 hover:text-purple-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-gamepad text-purple-500"></i> Oyuncu Sistemleri
                </a>
                <a href="{{ route('second-hand.index', ['category_id' => 4]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ request('category_id') == 4 ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-white text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-laptop text-emerald-500"></i> İkinci El Laptoplar
                </a>
                <a href="{{ route('second-hand.index', ['desktop_type' => ['Full Set']]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ in_array('Full Set', (array)request('desktop_type', [])) ? 'bg-blue-600 text-white shadow-blue-500/20' : 'bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-box-open text-blue-500"></i> Full Set Paketler
                </a>
                <a href="{{ route('second-hand.index', ['desktop_type' => ['Sadece Kasa']]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ in_array('Sadece Kasa', (array)request('desktop_type', [])) ? 'bg-amber-600 text-white shadow-amber-500/20' : 'bg-white text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-server text-amber-500"></i> Sadece Kasa
                </a>
                <a href="{{ route('second-hand.index', ['price_max' => 20000]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl whitespace-nowrap transition-all shadow-sm {{ request('price_max') == 20000 ? 'bg-rose-600 text-white shadow-rose-500/20' : 'bg-white text-gray-700 hover:bg-rose-50 hover:text-rose-700 border border-gray-200/80' }}">
                    <i class="fa-solid fa-fire text-rose-500"></i> 20.000 ₺ Altı Fırsatlar
                </a>
            </div>
        </div>

        {{-- ── İKİNCİ EL GÜVENCE BİLGİ ŞERİDİ ── --}}
        <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 border border-emerald-500/20 p-5 md:p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0 hidden sm:flex">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[11px] font-bold uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-check-double"></i> Şeffaf & Güvenli Alışveriş
                    </div>
                    <h2 class="text-base sm:text-lg font-black text-white">
                        Tüm İkinci El Ürünlerde <span class="text-emerald-400">1 Ay Donanım Garantisi</span>
                    </h2>
                    <p class="text-gray-300 text-xs mt-0.5 max-w-2xl leading-relaxed">
                        Cihazlarımız 32 nokta testinden geçirilerek teslim edilir. Olası bir donanım arızasında koşulsuz <strong class="text-emerald-300">birebir değişim hakkınız</strong> bulunmaktadır.
                    </p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-3">
                <a href="https://wa.me/905555555555?text={{ urlencode('Merhaba Afi Bilişim! İkinci el ürünleriniz hakkında bilgi almak istiyorum.') }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-emerald-500/20">
                    <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Danışma
                </a>
            </div>
        </div>

        {{-- Aktif filtre badge'leri --}}
        @php
            $hasAnyFilter = !empty($specFilters) || !empty($desktopTypeFilter) || request('category_id') || request('price_max') || request('brand') || request('search') || request('is_gaming');
        @endphp
        @if($hasAnyFilter)
        <div class="flex flex-wrap items-center gap-2 mb-6">
            {{-- Oyuncu Sistemleri badge --}}
            @if(request('is_gaming'))
                <a href="{{ request()->fullUrlWithQuery(['is_gaming' => null]) }}" class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 border border-purple-200 px-3 py-1 rounded-full text-xs font-semibold hover:bg-purple-100 transition" title="Filtreyi kaldır">
                    <i class="fa-solid fa-gamepad text-[9px]"></i> Oyuncu Sistemleri
                    <i class="fa-solid fa-xmark text-[10px] ml-0.5 text-purple-500"></i>
                </a>
            @endif
            {{-- Arama badge --}}
            @if(request('search'))
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 border border-emerald-300 px-3 py-1 rounded-full text-xs font-semibold hover:bg-emerald-200 transition" title="Aramayı kaldır">
                    <i class="fa-solid fa-magnifying-glass text-[9px]"></i> "{{ request('search') }}"
                    <i class="fa-solid fa-xmark text-[10px] ml-0.5 text-emerald-600"></i>
                </a>
            @endif
            {{-- Kategori badge --}}
            @if($activeCategory)
                <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold hover:bg-emerald-100 transition" title="Kategoriyi kaldır">
                    <i class="fa-solid fa-sitemap text-[9px]"></i> {{ $activeCategory->name }}
                    <i class="fa-solid fa-xmark text-[10px] ml-0.5 text-emerald-500"></i>
                </a>
            @endif
            {{-- Marka badge --}}
            @if(!empty($activeBrand))
                <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 border border-purple-200 px-3 py-1 rounded-full text-xs font-semibold hover:bg-purple-100 transition" title="Markayı kaldır">
                    <i class="fa-solid fa-tag text-[9px]"></i> {{ $activeBrand }}
                    <i class="fa-solid fa-xmark text-[10px] ml-0.5 text-purple-500"></i>
                </a>
            @endif
            {{-- Masaüstü Tipi badge'leri --}}
            @foreach((array)$desktopTypeFilter as $dtype)
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fa-solid fa-desktop text-[9px]"></i> {{ $dtype }}
                </span>
            @endforeach
            {{-- Spec badge'leri --}}
            @foreach($specFilters as $key => $vals)
                @foreach((array)$vals as $val)
                @php
                    $displayKeyName = $shSpecTranslations[strtolower($key)] ?? $key;
                @endphp
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fa-solid fa-microchip text-[9px]"></i> {{ $displayKeyName }}: {{ $val }}
                </span>
                @endforeach
            @endforeach
            {{-- Fiyat badge --}}
            @if(request('price_max'))
                <a href="{{ request()->fullUrlWithQuery(['price_max' => null]) }}" class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-800 border border-amber-200 px-3 py-1 rounded-full text-xs font-semibold hover:bg-amber-100 transition" title="Fiyat filtresini kaldır">
                    <i class="fa-solid fa-turkish-lira-sign text-[9px]"></i> Maks: {{ number_format(request('price_max'), 0, ',', '.') }} ₺
                    <i class="fa-solid fa-xmark text-[10px] ml-0.5 text-amber-600"></i>
                </a>
            @endif
            <a href="{{ route('second-hand.index') }}"
               class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-xs font-semibold hover:bg-red-100 transition">
                <i class="fa-solid fa-xmark"></i> Tümünü Temizle
            </a>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 w-full">

            {{-- ── SIDEBAR ── --}}
            <aside class="lg:col-span-1 w-full">
                <form id="sh-filter-form" method="GET" action="{{ route('second-hand.index') }}">
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" id="sh-hidden-sort" value="{{ request('sort') }}">
                    @endif

                    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm sticky top-28 space-y-5">

                        {{-- Başlık --}}
                        <div class="flex justify-between items-center">
                            <h2 class="text-base font-bold text-gray-800">
                                <i class="fa-solid fa-sliders text-emerald-500 mr-1.5"></i> Filtreler
                            </h2>
                            <a href="{{ route('second-hand.index') }}" class="text-[11px] font-bold text-gray-400 hover:text-red-500 transition">SIFIRLA</a>
                        </div>

                        {{-- Hızlı Arama --}}
                        <div>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="İkinci el ara (marka, model...)"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-8 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-gray-400 text-xs"></i>
                                @if(request('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute right-3 top-3 text-gray-400 hover:text-red-500 text-xs" title="Aramayı temizle">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Kategoriler & Alt Kategoriler --}}
                        <div>
                            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                <i class="fa-solid fa-sitemap text-emerald-500 mr-1"></i> Kategori
                            </h3>
                            @php
                                $currentShCatId = request('category_id', isset($activeCategory) ? $activeCategory->id : null);
                                $isAllShActive = empty($currentShCatId);
                            @endphp
                            <ul class="space-y-1.5">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}"
                                       class="sidebar-cat-btn flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold
                                              {{ $isAllShActive ? 'active-cat' : 'text-gray-600 hover:text-gray-900' }}">
                                        <span class="flex items-center gap-2.5">
                                            <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $isAllShActive ? 'bg-emerald-900 text-emerald-300' : 'bg-gray-100 text-gray-500' }}">
                                                <i class="fa-solid fa-recycle text-xs"></i>
                                            </span>
                                            Tüm İkinci El
                                        </span>
                                        <span class="text-[10px] {{ $isAllShActive ? 'bg-emerald-800 text-emerald-200' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full font-bold">
                                            {{ $totalUsed }}
                                        </span>
                                    </a>
                                </li>
                                @foreach($shCategories as $shCat)
                                    @php
                                        $isActive = $currentShCatId == $shCat->id;
                                        $catIcon  = $shCat->slug === 'laptop' ? 'fa-laptop' : ($shCat->slug === 'masaustu-bilgisayar' ? 'fa-desktop' : 'fa-layer-group');
                                        $hasChildren = $shCat->children && $shCat->children->count() > 0;
                                        $isChildActive = $hasChildren && $shCat->children->pluck('id')->contains($currentShCatId);
                                        $catTotal = $shCat->products_count + ($hasChildren ? $shCat->children->sum('products_count') : 0);
                                    @endphp
                                    <li>
                                        <a href="{{ request()->fullUrlWithQuery(['category_id' => $shCat->id]) }}"
                                           class="sidebar-cat-btn flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold
                                                  {{ ($isActive && !$isChildActive) ? 'active-cat' : 'text-gray-600 hover:text-gray-900' }}">
                                            <span class="flex items-center gap-2.5">
                                                <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ ($isActive && !$isChildActive) ? 'bg-emerald-900 text-emerald-300' : 'bg-gray-100 text-gray-500' }}">
                                                    <i class="fa-solid {{ $catIcon }} text-xs"></i>
                                                </span>
                                                {{ $shCat->name }}
                                            </span>
                                            @if($catTotal > 0)
                                                <span class="text-[10px] {{ ($isActive && !$isChildActive) ? 'bg-emerald-800 text-emerald-200' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full font-bold">
                                                    {{ $catTotal }}
                                                </span>
                                            @endif
                                        </a>
                                        @if($hasChildren)
                                            <ul class="pl-6 pt-1 pb-1 space-y-1">
                                                @foreach($shCat->children as $subCat)
                                                    @php
                                                        $isSubActive = $currentShCatId == $subCat->id;
                                                    @endphp
                                                    @if($subCat->products_count > 0 || $isSubActive)
                                                    <li>
                                                        <a href="{{ request()->fullUrlWithQuery(['category_id' => $subCat->id]) }}"
                                                           class="sub-cat-link flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs font-medium transition
                                                                  {{ $isSubActive ? 'bg-emerald-100 text-emerald-800 font-bold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                                                            <span class="flex items-center gap-1.5 truncate">
                                                                <i class="fa-solid fa-angle-right text-[9px] {{ $isSubActive ? 'text-emerald-600' : 'text-gray-400' }}"></i>
                                                                {{ $subCat->name }}
                                                            </span>
                                                            <span class="text-[9px] {{ $isSubActive ? 'bg-emerald-200 text-emerald-900' : 'bg-white text-gray-400 border border-gray-100' }} px-1.5 py-0.2 rounded font-bold">
                                                                {{ $subCat->products_count }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Marka Filtresi --}}
                        @if(isset($usedBrands) && $usedBrands->isNotEmpty())
                        <div>
                            <div class="h-px bg-gray-100 mb-4"></div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                    <i class="fa-solid fa-tag text-emerald-500 mr-1"></i> Marka
                                </h3>
                                @if(request('brand'))
                                    <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="text-[10px] text-gray-400 hover:text-red-500 font-bold transition">TEMİZLE</a>
                                @endif
                            </div>
                            <div class="space-y-1 max-h-52 overflow-y-auto pr-1">
                                @foreach($usedBrands as $b)
                                    @php
                                        $isBrandActive = request('brand') === $b->brand;
                                        $brandInputId  = 'brand-' . \Illuminate\Support\Str::slug($b->brand);
                                    @endphp
                                    <div>
                                        <input type="radio"
                                               class="spec-checkbox"
                                               id="{{ $brandInputId }}"
                                               name="brand"
                                               value="{{ $b->brand }}"
                                               {{ $isBrandActive ? 'checked' : '' }}
                                               onchange="document.getElementById('sh-filter-form').submit()">
                                        <label for="{{ $brandInputId }}" class="spec-checkbox-label group">
                                            <span class="spec-checkbox-box"></span>
                                            <span class="flex-1 text-xs font-semibold text-gray-700 truncate" title="{{ $b->brand }}">
                                                {{ $b->brand }}
                                            </span>
                                            <span class="text-[10px] {{ $isBrandActive ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-500' }} px-1.5 py-0.5 rounded-full font-bold shrink-0">
                                                {{ $b->count }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Masaüstü Tipi Filtresi — her zaman görünür --}}
                        <div>
                            <div class="h-px bg-gray-100 mb-4"></div>
                            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                <i class="fa-solid fa-desktop text-emerald-500 mr-1"></i> Masaüstü Tipi
                            </h3>
                            <div class="space-y-1">
                                @foreach([
                                    ['Full Set',       'fa-box-open', 'Monitör + Klavye/Mouse dahil'],
                                    ['Sadece Kasa',    'fa-server',   'Sadece masaüstü kasası'],
                                    ['Sadece Monitör', 'fa-display',  'Harici monitör satışı'],
                                ] as [$dtype, $dicon, $ddesc])
                                    @php
                                        $isChecked = in_array($dtype, (array)$desktopTypeFilter);
                                        $dcount    = $desktopTypeCounts[$dtype] ?? 0;
                                        $inputId   = 'dt-' . \Illuminate\Support\Str::slug($dtype);
                                    @endphp
                                    <div>
                                        <input type="checkbox"
                                               class="spec-checkbox"
                                               id="{{ $inputId }}"
                                               name="desktop_type[]"
                                               value="{{ $dtype }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               onchange="document.getElementById('sh-filter-form').submit()">
                                        <label for="{{ $inputId }}" class="spec-checkbox-label group {{ $dcount == 0 && !$isChecked ? 'opacity-50' : '' }}">
                                            <span class="spec-checkbox-box"></span>
                                            <span class="flex-1">
                                                <span class="flex items-center gap-1.5">
                                                    <i class="fa-solid {{ $dicon }} text-[11px] {{ $isChecked ? 'text-emerald-600' : 'text-gray-400' }}"></i>
                                                    {{ $dtype }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 block leading-tight">{{ $ddesc }}</span>
                                            </span>
                                            <span class="text-[10px] {{ $isChecked ? 'bg-emerald-500 text-white' : ($dcount > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-400') }} px-1.5 py-0.5 rounded-full font-bold shrink-0">
                                                {{ $dcount }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Fiyat Aralığı --}}
                        @if($priceRange['max'] > 0)
                        <div>
                            <div class="h-px bg-gray-100 mb-4"></div>
                            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                <i class="fa-solid fa-turkish-lira-sign text-emerald-500 mr-1"></i> Fiyat Aralığı
                            </h3>
                            <div class="px-1 space-y-2">
                                <input type="range" name="price_max" class="price-slider"
                                       min="{{ $priceRange['min'] }}" max="{{ $priceRange['max'] }}"
                                       value="{{ request('price_max', $priceRange['max']) }}"
                                       oninput="document.getElementById('sh-price-display').textContent = parseInt(this.value).toLocaleString('tr-TR') + ' ₺'">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ number_format($priceRange['min'],0,',','.') }} ₺</span>
                                    <span id="sh-price-display" class="text-emerald-700 font-bold">
                                        {{ number_format(request('price_max', $priceRange['max']),0,',','.') }} ₺
                                    </span>
                                </div>
                                <button type="submit" class="w-full py-2 bg-emerald-500 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 transition">
                                    Fiyat Uygula
                                </button>
                            </div>
                        </div>
                        @endif

                        {{-- Spec Filtreleri --}}
                        @if($specFilterOptions->isNotEmpty())
                        @php
                            $shSpecTranslations = [
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
                                'is_gaming'           => 'Oyuncu Bilgisayarı',
                            ];
                        @endphp
                        <div>
                            <div class="h-px bg-gray-100 mb-4"></div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                    <i class="fa-solid fa-microchip text-emerald-500 mr-1"></i> Özellikler
                                </h3>
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold">
                                    {{ $activeCategory->name ?? '' }}
                                </span>
                            </div>
                            <div class="space-y-2">
                                @foreach($specFilterOptions as $specKey => $specValues)
                                    @php
                                        $keyLower       = strtolower($specKey);
                                        $displaySpecKey = $shSpecTranslations[$keyLower] ?? ucwords(str_replace(['_', '-'], ' ', $specKey));
                                        $gId            = 'sh-spec-' . \Illuminate\Support\Str::slug($specKey);
                                        $curVals        = (array)($specFilters[$specKey] ?? []);
                                        $hasActive      = !empty(array_intersect($curVals, $specValues->toArray()));
                                    @endphp
                                    <div class="spec-group {{ $hasActive ? 'open' : '' }}" id="{{ $gId }}">
                                        <button type="button" onclick="toggleSpecGroup('{{ $gId }}')"
                                                class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm font-semibold transition-all
                                                       {{ $hasActive ? 'bg-emerald-50 text-emerald-800' : 'bg-gray-50 text-gray-700' }} hover:bg-emerald-50">
                                            <span>{{ $displaySpecKey }}</span>
                                            <i class="fa-solid fa-chevron-down text-[10px] spec-toggle-icon text-gray-400"></i>
                                        </button>
                                        <div class="spec-group-body">
                                            <div class="pt-1 pb-2">
                                                @foreach($specValues as $val)
                                                    @php $chk = in_array($val, $curVals); @endphp
                                                    <div>
                                                        <input type="checkbox" class="spec-checkbox"
                                                               id="{{ $gId }}-{{ \Illuminate\Support\Str::slug($val) }}"
                                                               name="specs[{{ $specKey }}][]"
                                                               value="{{ $val }}"
                                                               {{ $chk ? 'checked' : '' }}
                                                               onchange="document.getElementById('sh-filter-form').submit()">
                                                        <label for="{{ $gId }}-{{ \Illuminate\Support\Str::slug($val) }}" class="spec-checkbox-label">
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
                        @elseif(request('category_id'))
                            <p class="text-xs text-gray-400 text-center py-2">Bu kategoride özellik filtresi yok.</p>
                        @else
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-center">
                                <i class="fa-solid fa-hand-pointer text-emerald-500 text-lg mb-1"></i>
                                <p class="text-xs text-emerald-800 font-medium">Özellik filtresi için bir kategori seçin.</p>
                            </div>
                        @endif

                    </div>
                </form>
            </aside>

            {{-- ── ÜRÜN GRID ── --}}
            <main class="lg:col-span-3 min-w-0 w-full">

                {{-- Sonuç Özeti & Sıralama --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 bg-white rounded-2xl px-5 py-3 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <p class="text-sm text-gray-500">
                            <span class="font-black text-gray-800">{{ $products->total() }}</span> ikinci el ürün
                            @if($activeCategory) <span class="text-emerald-600 font-semibold">— {{ $activeCategory->name }}</span> @endif
                            @if(!empty($activeBrand)) <span class="text-purple-600 font-semibold">({{ $activeBrand }})</span> @endif
                        </p>
                        <span class="trust-badge bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] hidden md:inline-flex">
                            <i class="fa-solid fa-shield-check"></i> Tümü Test Edilmiş
                        </span>
                    </div>
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <label for="sh-sort-select" class="text-xs text-gray-400 font-medium whitespace-nowrap">
                            <i class="fa-solid fa-arrow-down-short-wide text-emerald-500 mr-1"></i> Sırala:
                        </label>
                        <select id="sh-sort-select"
                                class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-gray-700 font-semibold focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 cursor-pointer"
                                onchange="applySort(this.value)">
                            <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>En Yeni İlanlar</option>
                            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Fiyat: Artan</option>
                            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Fiyat: Azalan</option>
                        </select>
                    </div>
                </div>

                <div id="sh-product-area" class="relative">

                {{-- ✅ Ürün grid loading overlay --}}
                <div id="sh-grid-loader"
                     aria-hidden="true"
                     style="display:none; position:absolute; inset:0; z-index:40;
                            background:rgba(248,249,250,0.85); backdrop-filter:blur(4px);
                            border-radius:16px; min-height:200px;
                            align-items:center; justify-content:center; flex-direction:column; gap:14px;">
                    <div style="position:relative; width:56px; height:56px;">
                        <div style="position:absolute;inset:0;border-radius:50%;border:3px solid rgba(16,185,129,.15);"></div>
                        <div style="position:absolute;inset:0;border-radius:50%;border:3px solid transparent;
                                    border-top-color:#10B981;border-right-color:#059669;
                                    animation:afiSpin .8s cubic-bezier(.5,0,.5,1) infinite;"></div>
                        <div style="position:absolute;inset:17px;border-radius:50%;background:rgba(16,185,129,.1);
                                    display:flex;align-items:center;justify-content:center;">
                            <div style="width:8px;height:8px;border-radius:50%;background:#10B981;
                                        animation:afiPulse 1.2s ease-in-out infinite;"></div>
                        </div>
                    </div>
                    <p style="color:#10B981;font-family:'Outfit',sans-serif;font-weight:700;
                               font-size:11px;letter-spacing:.08em;text-transform:uppercase;
                               animation:afiPulse 1.3s ease-in-out infinite;">Filtreleniyor...</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 w-full">
                    @forelse($products as $product)
                        <div class="scroll-reveal-item w-full">
                            @include('products._demo_card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-span-3">
                            <div class="bg-white rounded-2xl p-16 text-center border border-gray-100">
                                <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-4"></i>
                                <h3 class="text-lg font-bold text-gray-400 mb-2">İkinci El Ürün Bulunamadı</h3>
                                <p class="text-sm text-gray-400 mb-6">Seçtiğiniz filtrelere uygun ürün bulunmamaktadır.</p>
                                <a href="{{ route('second-hand.index') }}"
                                   class="inline-flex items-center gap-2 bg-emerald-500 text-white font-bold px-6 py-3 rounded-xl hover:bg-emerald-600 transition text-sm">
                                    <i class="fa-solid fa-rotate-left"></i> Filtreleri Temizle
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
                @endif

                </div>{{-- /#sh-product-area --}}

            </main>
        </div>

        {{-- ── 4 AŞAMALI GÜVENCE STANDARTLARI ── --}}
        <div class="mt-16 pt-12 border-t border-gray-200">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-300">
                    Afi Bilişim Güvencesi
                </span>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 mt-3">
                    Neden Afi Bilişim İkinci El?
                </h2>
                <p class="text-gray-500 text-xs sm:text-sm mt-2">
                    Bireysel sahibinden riskine son; her ürün profesyonel teknisyenlerimizce incelenip garanti kapsamına alınır.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1.5">32 Nokta Donanım Testi</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        İşlemci (AIDA64/Prime95), ekran kartı (FurMark), RAM (MemTest86) ve SSD sağlık testlerinden %100 sorunsuz geçen ürünler satışa sunulur.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-spray-can-sparkles"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1.5">Termal Bakım & Temizlik</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        Tüm toz ve partiküller basınçlı hava ile arındırılır, yüksek iletkenlikli Arctic MX-4 termal macun yenilenerek serin çalışma sağlanır.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1.5">1 Ay Donanım Garantisi</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        Satın aldığınız her ikinci el ürün, teslim tarihinden itibaren 1 ay süreyle firmamızın parça ve teknik donanım garantisi altındadır.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-repeat"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1.5">Arızada Birebir Değişim</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">
                        Butik işletme politikamız gereği keyfi iade bulunmamaktadır. Ancak garanti süresince oluşabilecek donanım arızalarında birebir parça/cihaz değişimi sağlanır.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── KOZMETİK DERECELENDİRME REHBERİ ── --}}
        <div class="mt-14 bg-gradient-to-br from-emerald-950 via-gray-900 to-slate-900 rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-xl border border-emerald-500/20">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="max-w-2xl mb-8 relative z-10">
                <span class="text-emerald-400 font-bold text-xs uppercase tracking-widest bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">
                    Şeffaf Kondisyon Kriterleri
                </span>
                <h2 class="text-2xl md:text-3xl font-black text-white mt-3">
                    Ürün Durum Rehberi: Ne Aldığınızı Bilin
                </h2>
                <p class="text-gray-300 text-xs md:text-sm mt-2">
                    İkinci el ürünlerimizin tamamı donanımsal olarak %100 kusursuzdur. Kozmetik durumları ise 3 şeffaf kademede sınıflandırılır:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-emerald-400/50 transition">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-black mb-3">
                        <i class="fa-solid fa-gem"></i> Derece: A+ (Kusursuz)
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Sıfırdan Farksız / Teşhir</h3>
                    <p class="text-gray-300 text-xs leading-relaxed">
                        Çiziksiz, darbesiz, çoğu zaman orijinal kutusunda ya da vitrin ürünü olarak az kullanılmış en üst kondisyondaki cihazlardır.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-emerald-400/50 transition">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 text-xs font-black mb-3">
                        <i class="fa-solid fa-circle-check"></i> Derece: A (Çok Temiz)
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Çok Temiz Kondisyon</h3>
                    <p class="text-gray-300 text-xs leading-relaxed">
                        Sadece çok yakından bakıldığında görülebilecek mikro kullanım izleri haricinde pürüzsüz, içi tamamen yenilenmiş sistemlerdir.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:border-emerald-400/50 transition">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-black mb-3">
                        <i class="fa-solid fa-bolt"></i> Derece: B (Fiyat/Performans)
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Ekonomik & Güçlü</h3>
                    <p class="text-gray-300 text-xs leading-relaxed">
                        Kasa veya kapakta normal kullanıma bağlı hafif kılcal çizikleri bulunan, donanımı saat gibi işleyen en avantajlı bütçe dostu ürünlerdir.
                    </p>
                </div>
            </div>
        </div>

        {{-- ── ÖZEL İKİNCİ EL TOPLAMA & DANIŞMANLIK CTA ── --}}
        <div class="mt-14 bg-white rounded-3xl p-8 border border-emerald-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900">Aradığınız Sistemi Bulamadınız mı?</h3>
                    <p class="text-gray-500 text-xs sm:text-sm mt-1">
                        Bütçenizi ve oynamak istediğiniz oyunları bize söyleyin, depomuzdaki test edilmiş parçalarla size özel ikinci el sistem toplayalım!
                    </p>
                </div>
            </div>
            <div class="shrink-0 flex flex-wrap sm:flex-nowrap gap-3 w-full md:w-auto">
                <a href="https://wa.me/905555555555?text={{ urlencode('Merhaba Afi Bilişim! İkinci el özel kasa toplama veya sistem danışmanlığı hakkında bilgi almak istiyorum.') }}"
                   target="_blank"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-lg shadow-emerald-500/20">
                    <i class="fa-brands fa-whatsapp text-base"></i> Uzmanımıza Danışın
                </a>
                <a href="{{ route('pc-builder.index') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs uppercase tracking-wider transition">
                    <i class="fa-solid fa-screwdriver-wrench text-emerald-600"></i> PC Sihirbazı
                </a>
            </div>
        </div>

        {{-- ── SIKÇA SORULAN SORULAR ── --}}
        <div class="mt-14 max-w-4xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-gray-900">İkinci El Alışverişi Hakkında Merak Edilenler</h2>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">Aklınıza takılan soruların yanıtlarını burada bulabilirsiniz.</p>
            </div>

            <div class="space-y-3">
                @foreach([
                    [
                        'q' => 'İkinci el ürünlerin garantisi var mı?',
                        'a' => 'Evet! Afi Bilişim\'den satın aldığınız tüm ikinci el masaüstü bilgisayar, laptop ve donanım parçaları 1 ay süresince doğrudan firmamızın donanım ve teknik parça garantisi altındadır.'
                    ],
                    [
                        'q' => 'İade veya değişim imkanı var mı?',
                        'a' => 'Butik bir işletme olduğumuz için ikinci el ürünlerde keyfi iade kabul edilmemektedir. Ancak satın aldığınız üründe 1 aylık garanti süresi boyunca donanımsal bir arıza veya problem yaşanması halinde doğrudan birebir parça veya ürün değişimi yapılmaktadır.'
                    ],
                    [
                        'q' => 'Ürünü mağazanızda görüp test ederek alabilir miyim?',
                        'a' => 'Kesinlikle! Fiziksel mağazamıza gelerek beğendiğiniz ürünü FurMark, AIDA64 veya istediğiniz oyun ve programlarla bizzat test edebilir, uzman teknisyenlerimiz eşliğinde elden teslim alabilirsiniz.'
                    ],
                    [
                        'q' => 'İkinci el sistemde RAM veya SSD yükseltmesi yapabilir miyim?',
                        'a' => 'Elbette. Satın almak istediğiniz kasanın veya laptopun RAM, SSD ya da ekran kartını sipariş esnasında veya mağazamızda istediğiniz kapasiteye yükseltebilir, size özel konfigüre ettirebilirsiniz.'
                    ],
                    [
                        'q' => 'Kargo ile sipariş verirsem ürün nasıl paketlenir?',
                        'a' => 'Masaüstü kasalar ve laptoplar iç ve dış korumalı balonlu naylon, köpük destekli özel darbe emici kutularla paketlenir. Ekran kartı gibi ağır parçalar kargo esnasında yuvasına zarar vermemesi için özel sabitleme ile gönderilir.'
                    ],
                ] as $idx => $faq)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm transition">
                    <button type="button"
                            onclick="toggleFaq({{ $idx }})"
                            class="w-full flex items-center justify-between p-5 text-left font-bold text-gray-800 text-sm hover:text-emerald-700 transition">
                        <span>{{ $faq['q'] }}</span>
                        <i id="faq-icon-{{ $idx }}" class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div id="faq-ans-{{ $idx }}" class="hidden px-5 pb-5 text-xs text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
window.toggleFaq = function(idx) {
    const ans = document.getElementById('faq-ans-' + idx);
    const icon = document.getElementById('faq-icon-' + idx);
    if (ans) {
        ans.classList.toggle('hidden');
        if (icon) icon.classList.toggle('rotate-180');
    }
};
window.toggleCat = function(liEl) { if (liEl) liEl.classList.toggle('open'); };
window.toggleSpecGroup = function(id) {
    var el = typeof id === 'string' ? document.getElementById(id) : id;
    if (el) el.classList.toggle('open');
};

window.applySort = function(val) {
    const form = document.getElementById('sh-filter-form');
    if (!form) return;
    let sortInput = document.getElementById('sh-hidden-sort');
    if (!sortInput) {
        sortInput = document.createElement('input');
        sortInput.type = 'hidden';
        sortInput.name = 'sort';
        sortInput.id = 'sh-hidden-sort';
        form.appendChild(sortInput);
    }
    sortInput.value = val;
    form.submit();
};

async function toggleFavorite(btn, productId) {
    try {
        const res = await fetch(`/favorites/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        const data = await res.json();
        const icon  = btn.querySelector('i');
        const badge = document.getElementById('fav-badge');
        if (badge && data.favCount !== undefined) {
            badge.innerText = data.favCount;
            data.favCount > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
        }
        if (data.status === 'added') {
            btn.classList.replace('text-gray-400','text-red-500');
            icon.classList.replace('fa-regular','fa-solid');
        } else {
            btn.classList.replace('text-red-500','text-gray-400');
            icon.classList.replace('fa-solid','fa-regular');
        }
    } catch(e) { console.error(e); }
}
</script>

@push('scripts')
<script>
/* ─── İkinci El Sayfa — Scroll Pozisyonu Koruma & Filtre Loader ─── */
(function () {
    const filterForm = document.getElementById('sh-filter-form');
    const gridLoader = document.getElementById('sh-grid-loader');
    const priceBtn   = filterForm ? filterForm.querySelector('button[type="submit"]') : null;

    function saveScrollPos() {
        try {
            sessionStorage.setItem('sh_scroll_pos', window.scrollY);
        } catch (e) {}
    }

    function restoreScrollPos() {
        try {
            const savedPos = sessionStorage.getItem('sh_scroll_pos');
            if (savedPos !== null) {
                sessionStorage.removeItem('sh_scroll_pos');
                window.scrollTo({
                    top: parseInt(savedPos, 10),
                    behavior: 'instant'
                });
                return;
            }
        } catch (e) {}

        const urlParams = new URLSearchParams(window.location.search);
        const hasFilterParam = urlParams.has('category_id') || urlParams.has('desktop_type') || urlParams.has('specs') || urlParams.has('price_max') || urlParams.has('brand') || urlParams.has('search') || urlParams.has('sort') || urlParams.has('page') || window.location.hash.includes('sh-product-area');

        if (hasFilterParam) {
            const target = document.getElementById('sh-product-area') || document.getElementById('sh-filter-form');
            if (target) {
                setTimeout(() => {
                    const topOffset = target.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({
                        top: topOffset,
                        behavior: 'smooth'
                    });
                }, 80);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreScrollPos);
    } else {
        restoreScrollPos();
    }

    function showGridLoader(msg) {
        if (!gridLoader) return;
        if (msg) {
            const lbl = gridLoader.querySelector('p');
            if (lbl) lbl.textContent = msg;
        }
        gridLoader.style.display = 'flex';
    }

    if (filterForm) {
        /* Checkbox / radio değişimlerinde anlık submit */
        filterForm.addEventListener('change', function (e) {
            if (e.target.type === 'checkbox' || e.target.type === 'radio') {
                saveScrollPos();
                showGridLoader('Filtreleniyor...');
                if (window.AfiLoader) AfiLoader.show('Filtreler uygulanıyor...');
            }
        });

        /* Fiyat butonu ve genel submit */
        filterForm.addEventListener('submit', function () {
            saveScrollPos();
            showGridLoader('Arama yapılıyor...');
            if (window.AfiLoader) AfiLoader.show('Filtreler uygulanıyor...');
            if (priceBtn) AfiLoader && AfiLoader.btn(priceBtn);
        });
    }

    /* Kategori ve sayfalama linklerine de scroll kaydı ekle */
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link && link.href && (link.classList.contains('sidebar-cat-btn') || link.classList.contains('sub-cat-link') || link.closest('.pagination, nav[aria-label="Pagination"]'))) {
            saveScrollPos();
            showGridLoader('Yükleniyor...');
        }
    });
})();
</script>
@endpush
@endsection
