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
                    Her ürün uzman teknisyenlerimiz tarafından test edilmiş, temizlenmiş ve garantili olarak sunulmaktadır.
                    En uygun fiyata kaliteli teknoloji burada.
                </p>

                {{-- Trust badges --}}
                <div class="flex flex-wrap gap-3 mt-6">
                    @foreach([
                        ['fa-circle-check','Test Edilmiş'],
                        ['fa-shield-halved','14 Gün İade'],
                        ['fa-truck-fast','Hızlı Kargo'],
                        ['fa-file-invoice','Faturalı'],
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
                        <div class="text-[11px] text-gray-500">Garantili</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════
     İÇERİK: Sidebar + Grid
═══════════════════════════════════════════ --}}
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

        {{-- Aktif filtre badge'leri --}}
        @php
            $hasAnyFilter = !empty($specFilters) || !empty($desktopTypeFilter) || request('category_id') || request('price_max');
        @endphp
        @if($hasAnyFilter)
        <div class="flex flex-wrap gap-2 mb-6">
            {{-- Kategori badge --}}
            @if($activeCategory)
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fa-solid fa-sitemap text-[9px]"></i> {{ $activeCategory->name }}
                </span>
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

                    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm sticky top-28 space-y-5">

                        {{-- Başlık --}}
                        <div class="flex justify-between items-center">
                            <h2 class="text-base font-bold text-gray-800">
                                <i class="fa-solid fa-sliders text-emerald-500 mr-1.5"></i> Filtreler
                            </h2>
                            <a href="{{ route('second-hand.index') }}" class="text-[11px] font-bold text-gray-400 hover:text-red-500 transition">SIFIRLA</a>
                        </div>

                        {{-- Kategoriler: Sadece Laptop ve Masaüstü Bilgisayar --}}
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
                                    <a href="{{ route('second-hand.index') }}"
                                       class="sidebar-cat-btn flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-semibold
                                              {{ $isAllShActive ? 'active-cat' : 'text-gray-600 hover:text-gray-900' }}">
                                        <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $isAllShActive ? 'bg-emerald-900 text-emerald-300' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="fa-solid fa-recycle text-xs"></i>
                                        </span>
                                        Tüm İkinci El
                                    </a>
                                </li>
                                @foreach($shCategories as $shCat)
                                    @php
                                        $isActive = $currentShCatId == $shCat->id;
                                        $catIcon  = $shCat->slug === 'laptop' ? 'fa-laptop' : 'fa-desktop';
                                    @endphp
                                    <li>
                                        <a href="{{ route('second-hand.index') }}?category_id={{ $shCat->id }}"
                                           class="sidebar-cat-btn flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-semibold
                                                  {{ $isActive ? 'active-cat' : 'text-gray-600 hover:text-gray-900' }}">
                                            <span class="cat-icon-box w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ $isActive ? 'bg-emerald-900 text-emerald-300' : 'bg-gray-100 text-gray-500' }}">
                                                <i class="fa-solid {{ $catIcon }} text-xs"></i>
                                            </span>
                                            {{ $shCat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

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

                {{-- Sonuç Özeti --}}
                <div class="flex items-center justify-between mb-5 bg-white rounded-2xl px-5 py-3 border border-gray-100 shadow-sm">
                    <p class="text-sm text-gray-500">
                        <span class="font-black text-gray-800">{{ $products->total() }}</span> ikinci el ürün
                        @if($activeCategory) <span class="text-emerald-600 font-semibold">— {{ $activeCategory->name }}</span> @endif
                    </p>
                    <span class="trust-badge bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px]">
                        <i class="fa-solid fa-shield-check"></i> Tümü Test Edilmiş
                    </span>
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
    </div>
</div>

<script>
window.toggleCat = function(liEl) { if (liEl) liEl.classList.toggle('open'); };
window.toggleSpecGroup = function(id) {
    var el = typeof id === 'string' ? document.getElementById(id) : id;
    if (el) el.classList.toggle('open');
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
        const hasFilterParam = urlParams.has('category_id') || urlParams.has('desktop_type') || urlParams.has('specs') || urlParams.has('price_max') || urlParams.has('page') || window.location.hash.includes('sh-product-area');

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
