<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Afi Bilişim | Geleceğin Teknolojisi, Güvenli Çözümler')</title>
    <meta name="description" content="@yield('meta_description', 'Afi Bilişim — Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü ve güvenlik kamerası sistemleri. Uygun fiyat, hızlı kargo, Afi güvencesiyle.')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- ── Open Graph (Facebook, WhatsApp, LinkedIn) ── --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="Afi Bilişim">
    <meta property="og:url"         content="@yield('og_url', url()->current())">
    <meta property="og:title"       content="@yield('og_title', 'Afi Bilişim | Geleceğin Teknolojisi, Güvenli Çözümler')">
    <meta property="og:description" content="@yield('og_description', 'Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü ve güvenlik kamerası sistemleri.')">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale"      content="tr_TR">

    {{-- ── Twitter / X Card ── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:site"        content="@afibilisim">
    <meta name="twitter:title"       content="@yield('og_title', 'Afi Bilişim')">
    <meta name="twitter:description" content="@yield('og_description', 'Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü sistemleri.')">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- ── Sayfa-spesifik ek SEO etiketleri (JSON-LD vb.) ── --}}
    @stack('seo')

    {{-- ── Preconnect: hız için kritik alan bağlantıları ── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        afiGray: '#F8F9FA',
                        afiDark: '#111111',
                        afiAnthracite: '#1E1E1E',
                    },
                    backgroundImage: {
                        'afi-gradient': 'linear-gradient(135deg, #FFB300 0%, #F57C00 100%)',
                        'afi-dark-gradient': 'linear-gradient(135deg, #1E1E1E 0%, #111111 100%)',
                    }
                }
            }
        }
    </script>
    <style>
        /* ─── MODERN AMBIENT CSS MESH GRADIENT & GLOWING ATMOSPHERE ─── */
        body { 
            background-color: #0b0f19; 
            color: #ffffff; 
            overflow-x: hidden;
        }

        /* ─── KESİN ARKA PLAN DEKORATİF SVG VE ESKİ ANİMASYON ENGELLEYİCİ ─── */
        .circuit-lines, 
        .fan-animation, 
        .fan-spin,
        .bg-svg-decor, 
        .bg-animated-decor, 
        [class*="circuit"], 
        [class*="fan-spin"], 
        [id*="parallax-canvas"],
        svg[class*="hero-bg"],
        svg[class*="bg-decor"],
        svg.decor-bg,
        div[class*="background-svg"],
        div[class*="bg-pattern"] {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        .ambient-mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
            background: radial-gradient(circle at 50% 0%, #131824 0%, #0b0f19 75%);
        }

        .ambient-orb-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(234, 179, 8, 0.12) 0%, rgba(245, 124, 0, 0.05) 50%, transparent 70%);
            filter: blur(80px);
            animation: ambientGlow1 16s ease-in-out infinite alternate;
            will-change: transform, opacity;
        }

        .ambient-orb-2 {
            position: absolute;
            bottom: -15%;
            right: -10%;
            width: 750px;
            height: 750px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(234, 179, 8, 0.09) 0%, rgba(217, 119, 6, 0.04) 50%, transparent 70%);
            filter: blur(100px);
            animation: ambientGlow2 22s ease-in-out infinite alternate;
            will-change: transform, opacity;
        }

        .ambient-orb-3 {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(250, 204, 21, 0.05) 0%, transparent 65%);
            filter: blur(90px);
            animation: ambientPulse 12s ease-in-out infinite;
            will-change: transform, opacity;
        }

        @keyframes ambientGlow1 {
            0% { transform: translate(0, 0) scale(1); opacity: 0.7; }
            50% { transform: translate(60px, 40px) scale(1.15); opacity: 0.95; }
            100% { transform: translate(-30px, 80px) scale(1.05); opacity: 0.6; }
        }

        @keyframes ambientGlow2 {
            0% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            50% { transform: translate(-70px, -50px) scale(1.2); opacity: 0.9; }
            100% { transform: translate(40px, -80px) scale(0.95); opacity: 0.5; }
        }

        @keyframes ambientPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
            50% { transform: translate(-50%, -50%) scale(1.3); opacity: 0.85; }
        }

        .text-gradient {
            background: linear-gradient(135deg, #FFB300 0%, #F57C00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }
        .bg-gradient-btn {
            background: linear-gradient(135deg, #FFB300 0%, #F57C00 100%);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .bg-gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(245, 124, 0, 0.7);
        }
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
        }



        /* ─── 1MINUS1 SMOOTH & STAGGERED REVEAL SCROLL ─── */
        .scroll-reveal-item,
        .reveal-on-scroll {
            opacity: 0;
            transform: translate3d(0, 45px, 0) scale(0.98);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .scroll-reveal-item.is-revealed,
        .scroll-reveal-item.revealed,
        .reveal-on-scroll.is-revealed,
        .reveal-on-scroll.revealed {
            opacity: 1 !important;
            transform: translate3d(0, 0, 0) scale(1) !important;
        }

        /* ─── CYBERPUNK CARD & GLOW EFFECTS ─── */
        .product-card,
        .flip-card {
            position: relative;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
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
            height: 100% !important;
            display: block !important;
            box-sizing: border-box;
        }

        .card-hover {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .card-hover:hover {
            border-color: rgba(234, 179, 8, 0.5) !important;
            box-shadow: 0 10px 30px -10px rgba(234, 179, 8, 0.3), 0 0 20px 0 rgba(234, 179, 8, 0.15) !important;
            transform: translateY(-6px) scale(1.01) !important;
        }

        /* ─── AFI GLOBAL LOADER ─── */
        @keyframes afiSpin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes afiPulse {
            0%, 100% { opacity: 1;   transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.88); }
        }
        @keyframes afiLoaderIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        #afi-loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(17,17,17,0.80);
            backdrop-filter: blur(7px);
            -webkit-backdrop-filter: blur(7px);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 20px;
            animation: afiLoaderIn .25s ease;
        }
        #afi-loader-overlay.is-active { display: flex !important; }

        /* Dönen halka */
        .afi-ring-wrap {
            position: relative;
            width: 72px;
            height: 72px;
        }
        .afi-ring-track {
            position: absolute; inset: 0;
            border-radius: 50%;
            border: 3px solid rgba(255,179,0,0.12);
        }
        .afi-ring-arc {
            position: absolute; inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #FFB300;
            border-right-color: #F57C00;
            animation: afiSpin .85s cubic-bezier(0.5,0,0.5,1) infinite;
        }
        .afi-ring-core {
            position: absolute; inset: 20px;
            border-radius: 50%;
            background: rgba(255,179,0,0.10);
            display: flex; align-items: center; justify-content: center;
        }
        .afi-ring-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #FFB300;
            box-shadow: 0 0 12px 4px rgba(255,179,0,0.45);
            animation: afiPulse 1.4s ease-in-out infinite;
        }
        .afi-loader-label {
            color: #FFB300;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            animation: afiPulse 1.6s ease-in-out infinite;
        }

        /* ─── BUTON LOADER ─── */
        .afi-btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.80;
        }
        .afi-btn-spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: afiSpin .7s linear infinite;
            vertical-align: middle;
        }
        .afi-btn-spinner.yellow {
            border-color: rgba(255,179,0,0.25);
            border-top-color: #FFB300;
        }
    </style>
</head>
<body class="text-afiDark antialiased">

    <!-- Ambient Glowing CSS Mesh Background Atmosphere -->
    <div class="ambient-mesh-bg">
        <canvas id="afi-ambient-canvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
        <div class="ambient-orb-1"></div>
        <div class="ambient-orb-3"></div>
    </div>



    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-afiDark text-white py-3.5 px-4 md:px-8 lg:px-12 sticky top-0 z-[1000] shadow-xl border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 md:gap-6 flex-nowrap">
            <a href="{{ route('home') }}" class="text-xl md:text-2xl font-heading font-black tracking-tighter flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-microchip text-yellow-500"></i>
                <span>AFI<span class="text-yellow-500">BİLİŞİM</span></span>
            </a>
            <div class="hidden lg:flex items-center gap-4 xl:gap-6 font-medium text-xs xl:text-sm shrink-0">
                <a href="{{ route('home') }}" class="hover:text-yellow-400 transition py-2">Ana Sayfa</a>
                
                <!-- Mega Menu Dropdown -->
                <div class="relative group" x-data="{ activeCat: '{{ $globalCategories->first()->slug ?? 'bilesenler' }}' }">
                    <a href="{{ route('products.index') }}" class="hover:text-yellow-400 transition flex items-center gap-1.5 py-2 font-semibold">
                        <i class="fa-solid fa-layer-group text-xs text-yellow-500"></i>
                        Kategoriler <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180 text-gray-400"></i>
                    </a>
                    
                    <div class="absolute left-0 top-full pt-3 w-[860px] max-w-[90vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-3 group-hover:translate-y-0 z-50 pointer-events-none group-hover:pointer-events-auto">
                        <div class="bg-[#181c24] border border-gray-800 rounded-2xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.8)] overflow-hidden flex flex-col md:flex-row min-h-[400px] relative backdrop-blur-xl">
                            
                            <!-- Top Gradient Line -->
                            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600"></div>

                            <!-- Left Panel: Main Categories -->
                            <div class="w-full md:w-64 bg-[#12151c] border-r border-gray-800/80 p-3 flex flex-col justify-between shrink-0">
                                <div>
                                    <div class="px-3 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center justify-between border-b border-gray-800/60 mb-2">
                                        <span>Ana Kategoriler</span>
                                        <i class="fa-solid fa-bars-staggered text-yellow-500 text-xs"></i>
                                    </div>

                                    <ul class="space-y-1">
                                        @if(isset($globalCategories) && $globalCategories->count() > 0)
                                            @foreach($globalCategories as $mainCat)
                                                @if(in_array(strtolower($mainCat->slug), ['ssd-depolama', 'ssd', 'depolama']) || str_contains(strtolower($mainCat->name), 'ssd')) @continue @endif
                                                <li>
                                                    <button 
                                                        type="button"
                                                        @mouseenter="activeCat = '{{ $mainCat->slug }}'"
                                                        @click="window.location.href='{{ route('category.show', $mainCat->slug) }}'"
                                                        class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl transition-all text-sm font-semibold group/item cursor-pointer"
                                                        :class="activeCat === '{{ $mainCat->slug }}' ? 'bg-yellow-500 text-afiDark font-bold shadow-md shadow-yellow-500/20' : 'text-gray-300 hover:bg-gray-800/80 hover:text-white'"
                                                    >
                                                        <div class="flex items-center gap-2.5 min-w-0">
                                                            @php
                                                                $catIcon = match(strtolower($mainCat->slug)) {
                                                                    'bilesenler' => 'fa-microchip',
                                                                    'laptop' => 'fa-laptop',
                                                                    'masaustu-bilgisayar' => 'fa-desktop',
                                                                    'kamera-guvenlik' => 'fa-shield-halved',
                                                                    default => $mainCat->icon ?? 'fa-tags'
                                                                };
                                                            @endphp
                                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors shrink-0"
                                                                  :class="activeCat === '{{ $mainCat->slug }}' ? 'bg-black/10 text-afiDark' : 'bg-gray-800/80 text-yellow-500 group-hover/item:bg-gray-700'">
                                                                <i class="fa-solid {{ $catIcon }} text-xs"></i>
                                                            </span>
                                                            <span class="truncate text-xs md:text-sm">{{ $mainCat->name }}</span>
                                                        </div>
                                                        <i class="fa-solid fa-chevron-right text-[10px] transition-transform group-hover/item:translate-x-0.5"
                                                           :class="activeCat === '{{ $mainCat->slug }}' ? 'text-afiDark' : 'text-gray-500'"></i>
                                                    </button>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="pt-3 border-t border-gray-800/60 mt-3 px-1">
                                    <a href="{{ route('products.index') }}" class="flex items-center justify-between text-xs font-bold text-yellow-500 hover:text-yellow-400 p-2 rounded-lg hover:bg-yellow-500/10 transition">
                                        <span>Tüm Kataloğu Gör</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Right Panel: Subcategories -->
                            <div class="flex-1 p-5 md:p-6 bg-[#181c24] flex flex-col justify-between overflow-y-auto max-h-[460px]">
                                @if(isset($globalCategories) && $globalCategories->count() > 0)
                                    @foreach($globalCategories as $mainCat)
                                                @if(in_array(strtolower($mainCat->slug), ['ssd-depolama', 'ssd', 'depolama']) || str_contains(strtolower($mainCat->name), 'ssd')) @continue @endif
                                        <div x-show="activeCat === '{{ $mainCat->slug }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="h-full flex flex-col justify-between">
                                            <div>
                                                <!-- Header -->
                                                <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-800/80">
                                                    <div class="flex items-center gap-2.5">
                                                        <h3 class="text-base md:text-lg font-black text-white flex items-center gap-2">
                                                            <span>{{ $mainCat->name }}</span>
                                                        </h3>
                                                        <span class="text-[10px] bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2 py-0.5 rounded-full font-bold">
                                                            {{ $mainCat->children->count() }} Alt Kategori
                                                        </span>
                                                    </div>
                                                    <a href="{{ route('category.show', $mainCat->slug) }}" class="text-xs font-bold text-yellow-400 hover:text-yellow-300 flex items-center gap-1.5 bg-yellow-500/10 hover:bg-yellow-500/20 px-3 py-1.5 rounded-xl border border-yellow-500/30 transition">
                                                        <span>Tümünü Gör</span>
                                                        <i class="fa-solid fa-angle-right text-[10px]"></i>
                                                    </a>
                                                </div>

                                                <!-- Grid -->
                                                @if($mainCat->children->isNotEmpty())
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                                        @foreach($mainCat->children as $subCat)
                                                            @php
                                                                $subNameLower = mb_strtolower($subCat->name);
                                                                $subIcon = match(true) {
                                                                    str_contains($subNameLower, 'oyuncu') || str_contains($subNameLower, 'gaming') => 'fa-gamepad',
                                                                    str_contains($subNameLower, 'monitör') || str_contains($subNameLower, 'monitor') => 'fa-desktop',
                                                                    str_contains($subNameLower, 'işlemci') || str_contains($subNameLower, 'islemci') => 'fa-microchip',
                                                                    str_contains($subNameLower, 'ekran kartı') || str_contains($subNameLower, 'ekran karti') => 'gpu-svg',
                                                                    str_contains($subNameLower, 'ram') || str_contains($subNameLower, 'bellek') => 'fa-memory',
                                                                    str_contains($subNameLower, 'güç kaynağı') || str_contains($subNameLower, 'psu') => 'fa-plug',
                                                                    str_contains($subNameLower, 'hdd') || str_contains($subNameLower, 'sabit disk') => 'fa-hard-drive',
                                                                    str_contains($subNameLower, 'ssd') => 'fa-bolt-lightning',
                                                                    str_contains($subNameLower, 'anakart') => 'fa-server',
                                                                    str_contains($subNameLower, 'mouse') => 'fa-computer-mouse',
                                                                    str_contains($subNameLower, 'klavye') => 'fa-keyboard',
                                                                    str_contains($subNameLower, 'kulaklık') || str_contains($subNameLower, 'kulaklik') => 'fa-headphones',
                                                                    str_contains($subNameLower, 'mousepad') => 'fa-square',
                                                                    str_contains($subNameLower, 'ağ') || str_contains($subNameLower, 'modem') => 'fa-wifi',
                                                                    str_contains($subNameLower, 'kasa') || str_contains($subNameLower, 'case') => 'fa-computer',
                                                                     default => 'fa-box'
                                                                };
                                                            @endphp
                                                            <a href="{{ route('category.show', $subCat->slug) }}" 
                                                               class="flex items-center gap-2.5 p-2.5 rounded-xl bg-[#12151c]/60 hover:bg-gray-800 border border-gray-800/60 hover:border-yellow-500/40 transition-all duration-150 group/sub">
                                                                <div class="w-8 h-8 rounded-lg bg-gray-800/80 group-hover/sub:bg-yellow-500 group-hover/sub:text-afiDark text-yellow-500 flex items-center justify-center transition-all shrink-0 border border-gray-700/50 group-hover/sub:border-yellow-500 shadow-sm">
                                                                    @if($subIcon === 'gpu-svg')
                                                                        <svg class="w-4 h-4 fill-current transition-colors" viewBox="0 0 24 24">
                                                                            <path d="M3 4c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H3zm0 2h18v7H3V6zm1 9h16v2H4v-2zm3.5-7a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm9 0a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                                                        </svg>
                                                                    @else
                                                                        <i class="fa-solid {{ $subIcon }} text-xs"></i>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <h4 class="text-xs font-bold text-gray-200 group-hover/sub:text-yellow-400 transition-colors truncate">
                                                                        {{ $subCat->name }}
                                                                    </h4>
                                                                </div>
                                                                <i class="fa-solid fa-arrow-right text-[10px] text-gray-600 group-hover/sub:text-yellow-400 transition-all transform group-hover/sub:translate-x-0.5 shrink-0"></i>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <!-- Single Category Showcase Banner -->
                                                    <div class="bg-[#12151c]/60 border border-gray-800/60 rounded-2xl p-6 text-center flex flex-col items-center justify-center space-y-3">
                                                        <div class="w-12 h-12 rounded-full bg-yellow-500/10 text-yellow-500 flex items-center justify-center text-xl border border-yellow-500/20">
                                                            <i class="fa-solid fa-cubes"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-base font-bold text-white mb-1">{{ $mainCat->name }} Kategorisi</h4>
                                                            <p class="text-xs text-gray-400 max-w-sm">Tüm {{ $mainCat->name }} modellerini filtreleyerek hemen inceleyin.</p>
                                                        </div>
                                                        <a href="{{ route('category.show', $mainCat->slug) }}" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold px-5 py-2 rounded-xl text-xs transition shadow-lg shadow-yellow-500/20 inline-flex items-center gap-2">
                                                            <span>Ürünleri İncele</span>
                                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Bottom Info Bar -->
                                            <div class="mt-6 pt-3 border-t border-gray-800/60 flex items-center justify-between text-[11px] text-gray-400">
                                                <div class="flex items-center gap-4">
                                                    <span class="flex items-center gap-1.5 text-gray-300">
                                                        <i class="fa-solid fa-truck-fast text-yellow-500"></i> Hızlı Kargo
                                                    </span>
                                                    <span class="flex items-center gap-1.5 text-gray-300">
                                                        <i class="fa-solid fa-shield-check text-emerald-400"></i> Afi Güvencesi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                {{-- İkinci El Butonu --}}
                <a href="{{ route('second-hand.index') }}"
                   class="relative flex items-center gap-1.5 px-4 py-1.5 rounded-full font-bold text-sm transition-all
                          {{ request()->routeIs('second-hand.index')
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30'
                              : 'bg-emerald-500/15 text-emerald-400 hover:bg-emerald-500 hover:text-white border border-emerald-500/30' }}">
                    <i class="fa-solid fa-recycle text-xs"></i>
                    İkinci El
                    <span class="absolute -top-1.5 -right-1.5 text-[8px] bg-yellow-400 text-afiDark font-black px-1.5 py-0.5 rounded-full leading-none">
                        GÜVENCE
                    </span>
                </a>
                <a href="{{ route('pc-builder.index') }}" class="hover:text-yellow-400 transition py-2 font-bold flex items-center gap-1.5 text-yellow-400">
                    <i class="fa-solid fa-microchip"></i> PC Sihirbazı
                </a>
                <a href="{{ route('service-request.create') }}" class="hover:text-yellow-400 transition py-2 font-semibold">Teknik Servis</a>
                <button type="button" onclick="openServiceTrackingModal()" class="hover:bg-yellow-500 hover:text-afiDark text-yellow-400 border border-yellow-500/40 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 bg-yellow-500/10 shadow-sm cursor-pointer" title="Cihaz Servis Durumu Sorgula">
                    <i class="fa-solid fa-magnifying-glass text-yellow-400"></i> Servis Sorgula
                </button>
                <a href="{{ route('contact') }}" class="hover:text-yellow-400 transition py-2">İletişim</a>
            </div>

            <!-- Right Action Group -->
            <div class="flex items-center gap-3 md:gap-4 shrink-0 ml-auto lg:ml-0">
                <button type="button" onclick="openSearchModal()" class="text-white hover:text-yellow-400 transition cursor-pointer p-1" title="Ürün Ara">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
                
                @auth
                    <a href="{{ route('profile.notifications') }}" class="text-white hover:text-yellow-400 transition relative" title="Bildirimlerim">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @php $notifCount = \App\Models\PriceAlert::where('user_id', auth()->id())->where('is_notified', true)->where('is_read', false)->count(); @endphp
                        <span id="nav-notification-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-black w-4 h-4 flex items-center justify-center rounded-full {{ $notifCount > 0 ? '' : 'hidden' }}">
                            {{ $notifCount }}
                        </span>
                    </a>
                @endauth

                <a href="{{ route('favorites.index') }}" class="text-white hover:text-red-500 transition relative" title="Favorilerim">
                    <i class="fa-solid fa-heart text-lg"></i>
                </a>

                <button type="button" onclick="openCartDrawer()" class="text-white hover:text-yellow-400 transition relative cursor-pointer" title="Sepetim">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    <span id="cart-badge" class="absolute -top-2 -right-2 bg-yellow-500 text-afiDark text-[10px] font-black w-4 h-4 flex items-center justify-center rounded-full {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </button>

                <!-- Mobile Hamburger Button -->
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white hover:text-yellow-400 text-lg p-2 rounded-xl bg-gray-800/80 border border-gray-700/60 transition focus:outline-none cursor-pointer flex items-center justify-center w-9 h-9" aria-label="Mobil Menü">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-yellow-400' : 'fa-bars'"></i>
                </button>

                @auth
                    <div class="relative group">
                        <button class="hidden md:flex items-center gap-2 bg-gradient-btn text-afiDark font-bold px-6 py-2 rounded-full cursor-pointer">
                            Profilim <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute right-0 top-full pt-4 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 z-50">
                            <div class="bg-afiAnthracite border border-gray-800 rounded-xl shadow-2xl p-2 flex flex-col gap-1">
                                
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="text-yellow-500 hover:bg-gray-800 hover:text-yellow-400 px-4 py-2 rounded-lg transition text-sm font-bold flex items-center gap-2 border-b border-gray-800 pb-3 mb-1">
                                        <i class="fa-solid fa-shield-halved"></i> Afi Admin
                                    </a>
                                @endif

                                <a href="{{ route('profile') }}" class="text-white hover:bg-gray-800 hover:text-yellow-400 px-4 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-user"></i> Profilime Git
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-white hover:bg-red-500/10 hover:text-red-400 px-4 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-block bg-gradient-btn text-afiDark font-bold px-6 py-2 rounded-full">Giriş Yap</a>
                @endauth
            </div>
        </div>

        <!-- Mobile Navigation Off-Canvas Drawer -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden mt-3 pt-4 border-t border-gray-800/80 bg-[#12151c] rounded-2xl p-4 shadow-2xl space-y-4 text-sm font-medium"
             x-cloak>
            
            <div class="flex items-center justify-between pb-3 border-b border-gray-800/80">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menü & Navigasyon</span>
                <span class="text-xs text-yellow-400 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-bolt"></i> Afi Bilişim
                </span>
            </div>

            <!-- Ana Sayfa & Genel Linkler -->
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="openSearchModal(); mobileMenuOpen = false;" class="col-span-2 flex items-center justify-between p-2.5 rounded-xl bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 font-bold transition text-xs cursor-pointer">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-magnifying-glass"></i> Ürün Ara...</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-gray-800/60 hover:bg-gray-800 text-white font-semibold transition text-xs">
                    <i class="fa-solid fa-house text-yellow-500"></i> Ana Sayfa
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-gray-800/60 hover:bg-gray-800 text-white font-semibold transition text-xs">
                    <i class="fa-solid fa-layer-group text-yellow-500"></i> Tüm Ürünler
                </a>
                <a href="{{ route('second-hand.index') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold transition text-xs">
                    <i class="fa-solid fa-recycle text-emerald-400"></i> İkinci El
                </a>
                <a href="{{ route('pc-builder.index') }}" class="flex items-center gap-2 p-2.5 rounded-xl bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 font-bold transition text-xs">
                    <i class="fa-solid fa-microchip text-yellow-400"></i> PC Sihirbazı
                </a>
            </div>

            <!-- Kategoriler Listesi -->
            <div class="pt-2">
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Kategoriler</p>
                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                    @if(isset($globalCategories) && $globalCategories->count() > 0)
                        @foreach($globalCategories as $mainCat)
                            <a href="{{ route('category.show', $mainCat->slug) }}" class="flex items-center justify-between p-2 rounded-lg bg-gray-800/40 hover:bg-gray-800 text-gray-200 text-xs font-semibold transition">
                                <span>{{ $mainCat->name }}</span>
                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-500"></i>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Alt Butonlar & Servis -->
            <div class="pt-2 border-t border-gray-800/80 flex flex-col gap-2">
                <a href="{{ route('service-request.create') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-gray-800/60 text-gray-200 text-xs font-semibold hover:text-yellow-400 transition">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-wrench text-yellow-500"></i> Teknik Servis Talebi</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <button type="button" onclick="openServiceTrackingModal(); mobileMenuOpen = false;" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-yellow-500/10 text-yellow-400 border border-yellow-500/30 text-xs font-bold transition cursor-pointer">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-magnifying-glass"></i> Cihaz Servis Sorgula</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
                <a href="{{ route('contact') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-gray-800/60 text-gray-200 text-xs font-semibold hover:text-yellow-400 transition">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-envelope text-yellow-500"></i> İletişim</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Kullanıcı Oturum Butonları -->
            <div class="pt-3 border-t border-gray-800/80">
                @auth
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('profile') }}" class="flex-1 text-center bg-gray-800 text-white font-bold py-2 rounded-xl text-xs hover:bg-gray-700 transition">
                            Profilim
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center bg-yellow-500 text-afiDark font-bold py-2 rounded-xl text-xs hover:bg-yellow-400 transition">
                                Admin Paneli
                            </a>
                        @endif
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-gradient-btn text-afiDark font-bold py-2.5 rounded-xl text-xs shadow-lg shadow-yellow-500/20">
                        Giriş Yap / Kayıt Ol
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="app-footer" class="bg-afiDark text-white pt-20 pb-10 border-t border-gray-800 relative z-20">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Kurumsal -->
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="text-3xl font-heading font-black tracking-tighter flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-microchip text-yellow-500"></i>
                        <span>AFI<span class="text-yellow-500">BİLİŞİM</span></span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Teknolojinin sınırlarını zorlayan donanımlar ve 7/24 güvende hissetmenizi sağlayan yüksek teknolojili kamera sistemleriyle geleceği bugünden yaşayın.
                    </p>
                    <div class="flex gap-4 text-gray-400">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-500 hover:text-afiDark transition-all duration-300 transform hover:-translate-y-1"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-500 hover:text-afiDark transition-all duration-300 transform hover:-translate-y-1"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-500 hover:text-afiDark transition-all duration-300 transform hover:-translate-y-1"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-500 hover:text-afiDark transition-all duration-300 transform hover:-translate-y-1"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Hızlı Bağlantılar -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2"><i class="fa-solid fa-link text-yellow-500 text-sm"></i> Hızlı Bağlantılar</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-yellow-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs"></i> Ana Sayfa</a></li>
                        <li><a href="{{ route('products.index') }}?condition=new" class="text-gray-400 hover:text-yellow-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs"></i> Sıfır Bilgisayarlar</a></li>
                        <li><a href="{{ route('products.index') }}?condition=used" class="text-gray-400 hover:text-yellow-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs"></i> İkinci El Bilgisayarlar</a></li>
                        <li><a href="{{ route('products.index') }}?category=kamera" class="text-gray-400 hover:text-yellow-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs"></i> Kamera Sistemleri</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-yellow-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs"></i> İletişim</a></li>
                    </ul>
                </div>

                <!-- İletişim -->
                <div class="lg:col-span-2">
                    <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2"><i class="fa-solid fa-headset text-yellow-500 text-sm"></i> İletişim Bilgileri</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 text-yellow-500 flex items-center justify-center shrink-0 text-xl">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold mb-1">Adres</p>
                                <p class="text-gray-400 text-sm">Teknoloji Mah. Bilişim Cad. No:1 Merkez / Türkiye</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 text-yellow-500 flex items-center justify-center shrink-0 text-xl">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold mb-1">Telefon</p>
                                <a href="tel:+905555555555" class="text-gray-400 text-sm hover:text-yellow-400 transition-colors">0555 555 55 55</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 text-yellow-500 flex items-center justify-center shrink-0 text-xl">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold mb-1">E-Posta</p>
                                <a href="mailto:info@afibilisim.com" class="text-gray-400 text-sm hover:text-yellow-400 transition-colors">info@afibilisim.com</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-800 text-green-500 flex items-center justify-center shrink-0 text-xl">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold mb-1">WhatsApp</p>
                                <a href="https://wa.me/905555555555" target="_blank" class="text-gray-400 text-sm hover:text-green-400 transition-colors">7/24 Hızlı Destek</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alt Bilgi (Copyright) -->
            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Afi Bilişim. Tüm hakları saklıdır.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-yellow-400 transition">Gizlilik Politikası</a>
                    <a href="#" class="hover:text-yellow-400 transition">Kullanım Koşulları</a>
                    <a href="#" class="hover:text-yellow-400 transition">Mesafeli Satış Sözleşmesi</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        /**
         * Ürün kartı click-toggle flip fonksiyonu.
         * Hover efekti tamamen kaldırılmıştır; dönme yalnızca tıklama ile gerçekleşir.
         */
        function toggleCard(cardEl) {
            cardEl.classList.toggle('flipped');
        }

        async function addToCartQuick(btn, productId) {
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btn.disabled = true;

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
                
                if (data.status === 'success') {
                    // Update header badge
                    const badge = document.getElementById('cart-badge');
                    if(badge) {
                        badge.innerText = data.cartCount;
                        badge.classList.remove('hidden');
                        badge.classList.add('scale-150');
                        setTimeout(() => badge.classList.remove('scale-150'), 300);
                    }

                    // Show success icon
                    btn.classList.remove('bg-yellow-500', 'text-afiDark');
                    btn.classList.add('!bg-green-500', '!text-white');
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    
                    // Simple Toast
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-5 right-5 bg-green-500 text-white px-6 py-3 rounded-xl shadow-2xl font-bold z-50 flex items-center gap-3 transform translate-y-full opacity-0 transition-all duration-300';
                    toast.innerHTML = '<i class="fa-solid fa-circle-check text-xl"></i> Ürün sepete eklendi!';
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.classList.remove('translate-y-full', 'opacity-0');
                    }, 10);

                    setTimeout(() => {
                        toast.classList.add('translate-y-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                        
                        btn.classList.add('bg-yellow-500', 'text-afiDark');
                        btn.classList.remove('!bg-green-500', '!text-white');
                        btn.innerHTML = originalIcon;
                        btn.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Hata oluştu');
                }
            } catch (error) {
                console.error("Sepet Hatası:", error);
                btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                btn.classList.remove('bg-yellow-500');
                btn.classList.add('!bg-red-500', '!text-white');
                
                setTimeout(() => {
                    btn.classList.add('bg-yellow-500');
                    btn.classList.remove('!bg-red-500', '!text-white');
                    btn.innerHTML = originalIcon;
                    btn.disabled = false;
                }, 2000);
            }
        }
    </script>

    @auth
    @if(request()->route() && request()->route()->getName() !== 'profile.notifications')
        @php
            $hasNewNotifications = \App\Models\PriceAlert::where('user_id', auth()->id())
                ->where('is_notified', true)
                ->where('is_read', false)
                ->exists();
        @endphp
        @if($hasNewNotifications)
            <div id="price-alert-toast" class="fixed bottom-5 right-5 bg-white border-2 border-yellow-500 text-afiDark p-4 rounded-2xl shadow-2xl z-[100] flex items-start gap-4 max-w-sm transform translate-y-full opacity-0 transition-all duration-500 cursor-pointer" onclick="window.location.href='{{ route('profile.notifications') }}'">
                <div class="bg-yellow-100 text-yellow-600 w-12 h-12 rounded-full flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-bell fa-shake"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="font-bold text-lg leading-tight">Fiyat Alarmı!</h4>
                        <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider">Yeni</span>
                    </div>
                    <p class="text-sm text-gray-600 leading-snug">Takip ettiğiniz ürünlerden birinin fiyatı düştü. İncelemek için tıklayın.</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-700 p-1" onclick="event.stopPropagation(); document.getElementById('price-alert-toast').classList.add('translate-y-full', 'opacity-0'); setTimeout(() => document.getElementById('price-alert-toast').remove(), 500);">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(() => {
                        const toast = document.getElementById('price-alert-toast');
                        if(toast) {
                            toast.classList.remove('translate-y-full', 'opacity-0');
                        }
                    }, 500);
                });
            </script>
        @endif
    @endif
    @endauth

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- GLOBAL OVERLAY LOADER                          --}}
    {{-- JS ile window.AfiLoader.show() / .hide() ile  --}}
    {{-- tetiklenir; sayfa geçişlerinde otomatik açılır --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div id="afi-loader-overlay" role="status" aria-label="Yükleniyor" aria-live="polite">
        <div class="afi-ring-wrap">
            <div class="afi-ring-track"></div>
            <div class="afi-ring-arc"></div>
            <div class="afi-ring-core">
                <div class="afi-ring-dot"></div>
            </div>
        </div>
        <p class="afi-loader-label" id="afi-loader-label">Yükleniyor...</p>
    </div>

    @stack('scripts')

    <script>
    /* ═══════════════════════════════════════════════════════
       AFI GLOBAL LOADER — window.AfiLoader
       ─────────────────────────────────────────────────────
       API:
         AfiLoader.show(msg?)  →  Overlay aç
         AfiLoader.hide()      →  Overlay kapat
         AfiLoader.btn(el, msg?) → Butonu loading state'e al
         AfiLoader.btnReset(el)  → Butonu orijinal haline döndür
    ═══════════════════════════════════════════════════════ */
    (function () {
        const overlay = document.getElementById('afi-loader-overlay');
        const label   = document.getElementById('afi-loader-label');

        window.AfiLoader = {
            show(msg) {
                if (!overlay) return;
                if (msg && label) label.textContent = msg;
                overlay.classList.add('is-active');
                document.body.style.overflow = 'hidden';
            },
            hide() {
                if (!overlay) return;
                overlay.classList.remove('is-active');
                document.body.style.overflow = '';
            },
            /* Butona loading spinner ekle, orijinal HTML'i sakla */
            btn(el, msg) {
                if (!el) return;
                el.dataset.afiOriginal = el.innerHTML;
                el.classList.add('afi-btn-loading');
                el.disabled = true;
                const spinnerClass = el.classList.contains('text-white') || el.classList.contains('bg-adminDark') ? '' : 'yellow';
                el.innerHTML = `<span class="afi-btn-spinner ${spinnerClass}"></span>${msg ? ' ' + msg : ''}`;
            },
            btnReset(el) {
                if (!el || !el.dataset.afiOriginal) return;
                el.innerHTML   = el.dataset.afiOriginal;
                el.disabled    = false;
                el.classList.remove('afi-btn-loading');
                delete el.dataset.afiOriginal;
            }
        };

        /* ─── Sayfa Geçiş Loader (tüm <a> linkleri) ─── */
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href]');
            if (!anchor) return;

            const href = anchor.getAttribute('href');
            // Sadece gerçek sayfa geçişlerinde tetikle
            if (!href
                || href.startsWith('#')
                || href.startsWith('javascript')
                || href.startsWith('mailto')
                || href.startsWith('tel')
                || href.startsWith('wa.me')
                || anchor.target === '_blank'
                || e.ctrlKey || e.metaKey || e.shiftKey
            ) return;

            // Ajax/fetch linkleri için data-no-loader="true" eklenebilir
            if (anchor.dataset.noLoader === 'true') return;

            // Aynı sayfa hash değişimi ise gösterme
            try {
                const url = new URL(href, window.location.origin);
                if (url.pathname === window.location.pathname
                    && url.search === window.location.search) return;
            } catch (_) {}

            AfiLoader.show('Sayfa yükleniyor...');
        });

        /* ─── Tarayıcı geri/ileri tuşunda loader gizle ─── */
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) AfiLoader.hide();
        });

        /* ─── Form submit loader (data-loader-form niteliği olanlarda) ─── */
        /* Spesifik form ID'leri sayfa dosyasında bağlanır (second-hand vb.) */

        /* ─── Global Scroll Reveal Observer ─── */
        function initGlobalScrollReveal() {
            const revealItems = document.querySelectorAll('.scroll-reveal-item, .reveal-on-scroll');
            if (!revealItems.length) return;

            if (!('IntersectionObserver' in window)) {
                revealItems.forEach(el => el.classList.add('is-revealed', 'revealed'));
                return;
            }

            const observerOptions = {
                root: null,
                rootMargin: '200px 0px 200px 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const delay = parseInt(el.dataset.revealDelay || el.dataset.delay || 0, 10);
                        setTimeout(() => {
                            el.classList.add('is-revealed', 'revealed');
                        }, delay);
                        obs.unobserve(el);
                    }
                });
            }, observerOptions);

            revealItems.forEach((el, idx) => {
                if (!el.dataset.revealDelay && !el.dataset.delay) {
                    el.dataset.revealDelay = (idx % 3) * 80;
                }
                observer.observe(el);
            });

            /* Fallback: reveal everything after 1.5s in case observer misses items */
            setTimeout(() => {
                document.querySelectorAll('.scroll-reveal-item:not(.is-revealed), .reveal-on-scroll:not(.is-revealed)')
                    .forEach(el => el.classList.add('is-revealed', 'revealed'));
            }, 1500);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initGlobalScrollReveal);
        } else {
            initGlobalScrollReveal();
        }
    })();
    </script>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- SERVICE TRACKING MODAL (Servis Takip Modalı)   -->
    <!-- ═══════════════════════════════════════════════ -->
    <div id="serviceTrackingModal" class="fixed inset-0 z-[2000] hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-afiDark border border-gray-800 text-white rounded-3xl max-w-xl w-full p-6 md:p-8 shadow-2xl relative overflow-hidden">
            
            <!-- Background Glow -->
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-yellow-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Close Button -->
            <button type="button" onclick="closeServiceTrackingModal()" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors text-xl w-10 h-10 rounded-full bg-gray-800/60 flex items-center justify-center border border-gray-700 cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Modal Title Header -->
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-yellow-500/10 text-yellow-500 flex items-center justify-center text-2xl border border-yellow-500/20 shrink-0">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white">Servis Durumu Sorgulama</h3>
                    <p class="text-xs text-gray-400">Takip numaranız veya kayıtlı telefon numaranız ile anlık durum öğrenin.</p>
                </div>
            </div>

            <!-- Form -->
            <form id="serviceTrackingForm" onsubmit="handleServiceTrackingSubmit(event)" class="mb-6 space-y-4">
                <div class="relative">
                    <input type="text" id="serviceTrackingCodeInput" name="tracking_code" required placeholder="Cihaz Servis Takip Kodu veya Telefon Numarası girin..." class="w-full bg-gray-900 border border-gray-700 rounded-2xl px-5 py-4 pl-12 text-white focus:ring-2 focus:ring-yellow-500 focus:outline-none transition text-sm font-semibold">
                    <i class="fa-solid fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                </div>
                <button type="submit" id="serviceTrackingSubmitBtn" class="w-full bg-yellow-500 hover:bg-yellow-400 text-afiDark font-black py-3.5 rounded-2xl text-base transition-all shadow-lg shadow-yellow-500/20 flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-magnifying-glass"></i> Cihazı Sorgula
                </button>
            </form>

            <!-- Loading State -->
            <div id="serviceTrackingLoading" class="hidden py-8 text-center text-yellow-500">
                <i class="fa-solid fa-spinner fa-spin text-3xl mb-3"></i>
                <p class="text-sm font-bold text-gray-300">Servis kaydı sorgulanıyor...</p>
            </div>

            <!-- Error State -->
            <div id="serviceTrackingError" class="hidden bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-2xl text-sm font-medium text-center mb-4">
            </div>

            <!-- Results Container -->
            <div id="serviceTrackingResult" class="hidden space-y-5 border-t border-gray-800 pt-6">
                <!-- Device & Code Info Header -->
                <div class="bg-gray-900/80 p-4 rounded-2xl border border-gray-800 flex justify-between items-center">
                    <div>
                        <span id="stResultCode" class="bg-yellow-500 text-afiDark text-xs font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">SR-00001</span>
                        <h4 id="stResultDevice" class="font-bold text-white text-base mt-2">Cihaz Modeli</h4>
                        <p id="stResultType" class="text-xs text-gray-400 mt-0.5">Teknik Servis</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-gray-500">Kayıt Tarihi</p>
                        <p id="stResultDate" class="text-xs font-bold text-gray-300">01.01.2026 12:00</p>
                        <p id="stResultCustomer" class="text-[11px] text-gray-400 mt-1 font-medium">Müşteri: <span class="text-white font-bold">A*** K***</span></p>
                    </div>
                </div>

                <!-- Stepper Stages Progress Bar -->
                <div>
                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 text-center">Cihaz Onarım Aşamaları</h5>
                    <div class="grid grid-cols-3 gap-2 text-center relative">
                        
                        <!-- Step 1 -->
                        <div id="stStep1" class="flex flex-col items-center gap-2 relative z-10">
                            <div class="w-10 h-10 rounded-full bg-gray-800 border-2 border-gray-700 text-gray-500 flex items-center justify-center text-sm font-bold step-icon transition-all">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400 step-label">1. Kayıt Alındı</span>
                        </div>

                        <!-- Step 2 -->
                        <div id="stStep2" class="flex flex-col items-center gap-2 relative z-10">
                            <div class="w-10 h-10 rounded-full bg-gray-800 border-2 border-gray-700 text-gray-500 flex items-center justify-center text-sm font-bold step-icon transition-all">
                                <i class="fa-solid fa-microchip"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400 step-label">2. İnceleme & İşlemde</span>
                        </div>

                        <!-- Step 3 -->
                        <div id="stStep3" class="flex flex-col items-center gap-2 relative z-10">
                            <div class="w-10 h-10 rounded-full bg-gray-800 border-2 border-gray-700 text-gray-500 flex items-center justify-center text-sm font-bold step-icon transition-all">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400 step-label">3. Tamamlandı / Hazır</span>
                        </div>

                    </div>
                </div>

                <!-- Issue Description & Admin Note -->
                <div class="space-y-3 pt-2">
                    <div class="bg-gray-900/50 p-3.5 rounded-xl border border-gray-800 text-xs">
                        <span class="text-gray-500 font-bold uppercase text-[10px]">Bildirilen Arıza / Sorun:</span>
                        <p id="stResultIssue" class="text-gray-300 mt-1 leading-relaxed"></p>
                    </div>

                    <div id="stAdminNoteBox" class="hidden bg-yellow-500/10 border border-yellow-500/30 p-3.5 rounded-xl text-xs">
                        <span class="text-yellow-400 font-bold uppercase text-[10px] flex items-center gap-1">
                            <i class="fa-solid fa-user-gear"></i> Servis Tekniker Notu:
                        </span>
                        <p id="stResultAdminNote" class="text-yellow-200 mt-1 leading-relaxed font-medium"></p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ─── GLOBAL E-COMMERCE SEARCH MODAL OVERLAY ─── -->
    <div id="globalSearchModal" class="fixed inset-0 z-[99999] hidden flex items-start justify-center pt-12 sm:pt-20 px-4 bg-black/80 backdrop-blur-md transition-all duration-300">
        <div class="fixed inset-0" onclick="closeSearchModal()"></div>
        
        <div class="relative w-full max-w-2xl bg-[#12151c] border border-gray-800 rounded-3xl shadow-[0_25px_70px_rgba(0,0,0,0.9)] overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-200">
            <!-- Header Accent Line -->
            <div class="h-1 bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-600"></div>

            <!-- Search Form -->
            <form action="{{ route('products.index') }}" method="GET" class="p-4 sm:p-6 space-y-4">
                <div class="flex items-center justify-between gap-3 pb-3 border-b border-gray-800/80">
                    <span class="text-xs font-bold text-yellow-500 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i> Afi Bilişim Ürün Arama
                    </span>
                    <button type="button" onclick="closeSearchModal()" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white flex items-center justify-center transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <div class="relative flex items-center">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 text-yellow-500 text-lg pointer-events-none"></i>
                    <input type="text" 
                           id="globalSearchInput" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Ürün adı, model, marka veya kategori yazın..." 
                           autocomplete="off"
                           oninput="onLiveSearchInput(this.value)"
                           class="w-full bg-gray-900/90 border-2 border-gray-700 focus:border-yellow-500 rounded-2xl pl-12 pr-28 py-3.5 text-white text-sm sm:text-base font-semibold placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-yellow-500/20 transition-all">
                    <button type="submit" class="absolute right-2 bg-gradient-btn text-afiDark font-black text-xs px-4 py-2 rounded-xl hover:scale-105 transition-all shadow-md flex items-center gap-1.5 cursor-pointer">
                        <span>ARA</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>

                <!-- Live Search Dropdown Container -->
                <div id="liveSearchResults" class="hidden max-h-80 overflow-y-auto rounded-2xl bg-gray-900/95 border border-gray-800 divide-y divide-gray-800/80 shadow-2xl transition-all">
                    <!-- Populated dynamically via JS -->
                </div>

                <!-- Quick Search Suggestions / Badges -->
                <div class="pt-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-fire text-yellow-500"></i> Popüler Aramalar
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="quickSearch('İşlemci')" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-yellow-500 hover:text-afiDark text-gray-300 text-xs font-semibold transition border border-gray-700/60 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-microchip text-yellow-500"></i> İşlemciler
                        </button>
                        <button type="button" onclick="quickSearch('Ekran Kartı')" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-yellow-500 hover:text-afiDark text-gray-300 text-xs font-semibold transition border border-gray-700/60 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-desktop text-yellow-500"></i> Ekran Kartları
                        </button>
                        <button type="button" onclick="quickSearch('SSD')" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-yellow-500 hover:text-afiDark text-gray-300 text-xs font-semibold transition border border-gray-700/60 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-hard-drive text-yellow-500"></i> SSD'ler
                        </button>
                        <button type="button" onclick="quickSearch('Gaming Laptop')" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-yellow-500 hover:text-afiDark text-gray-300 text-xs font-semibold transition border border-gray-700/60 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-laptop text-yellow-500"></i> Gaming Laptop
                        </button>
                        <button type="button" onclick="quickSearch('Monitör')" class="px-3 py-1.5 rounded-xl bg-gray-800/80 hover:bg-yellow-500 hover:text-afiDark text-gray-300 text-xs font-semibold transition border border-gray-700/60 flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-tv text-yellow-500"></i> Monitör
                        </button>
                        <a href="{{ route('second-hand.index') }}" onclick="closeSearchModal()" class="px-3 py-1.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-emerald-400 text-xs font-bold transition border border-emerald-500/30 flex items-center gap-1.5">
                            <i class="fa-solid fa-recycle"></i> İkinci El Ürünler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    let liveSearchTimer = null;

    function openSearchModal() {
        const modal = document.getElementById('globalSearchModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(function() {
            const input = document.getElementById('globalSearchInput');
            if (input) {
                input.focus();
                if (input.value.trim().length >= 2) {
                    onLiveSearchInput(input.value);
                }
            }
        }, 50);
    }

    function closeSearchModal() {
        const modal = document.getElementById('globalSearchModal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function quickSearch(keyword) {
        const input = document.getElementById('globalSearchInput');
        if (input) {
            input.value = keyword;
            onLiveSearchInput(keyword);
            input.focus();
        }
    }

    function onLiveSearchInput(val) {
        clearTimeout(liveSearchTimer);
        const resultsContainer = document.getElementById('liveSearchResults');
        if (!resultsContainer) return;

        const query = val.trim();
        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
            return;
        }

        // Loading state
        resultsContainer.classList.remove('hidden');
        resultsContainer.innerHTML = `
            <div class="p-4 text-center text-xs text-gray-400 flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin text-yellow-500"></i>
                <span>Aranıyor: <strong class="text-white">"${query}"</strong></span>
            </div>
        `;

        liveSearchTimer = setTimeout(async function() {
            try {
                const response = await fetch(`{{ route('products.search.api') }}?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (!data.success || data.count === 0) {
                    resultsContainer.innerHTML = `
                        <div class="p-5 text-center space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-yellow-500/10 text-yellow-400 flex items-center justify-center text-lg border border-yellow-500/20 mb-1">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </div>
                            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Aradığınız kriterlere uygun ürün bulunamadı</h5>
                            <p class="text-[11px] text-gray-400">Farklı bir kelime deneyebilir veya yukarıdaki hızlı arama etiketlerini seçebilirsiniz.</p>
                        </div>
                    `;
                    return;
                }

                let html = `
                    <div class="p-2.5 border-b border-gray-800 bg-gray-950/60 flex items-center justify-between text-[11px]">
                        <span class="text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5"><i class="fa-solid fa-list-ul text-yellow-500"></i> Eşleşen Ürünler (${data.count})</span>
                        <a href="{{ route('products.index') }}?search=${encodeURIComponent(query)}" onclick="closeSearchModal()" class="text-yellow-400 hover:underline font-bold flex items-center gap-1">Tümünü Gör <i class="fa-solid fa-arrow-right text-[9px]"></i></a>
                    </div>
                `;

                data.results.forEach(product => {
                    const badgeHtml = product.is_used 
                        ? `<span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[9px] font-extrabold px-2 py-0.5 rounded-md">İkinci El</span>` 
                        : (product.badge ? `<span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-[9px] font-extrabold px-2 py-0.5 rounded-md">${product.badge}</span>` : '');

                    html += `
                        <a href="${product.url}" class="flex items-center gap-3 p-3 hover:bg-gray-800/80 transition group">
                            <img src="${product.image}" alt="${product.title}" class="w-12 h-12 object-contain rounded-lg bg-gray-950 p-1 border border-gray-800 shrink-0 group-hover:scale-105 transition-transform">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-[10px] font-bold text-yellow-500/80 uppercase tracking-wider">${product.category_name}</span>
                                    ${badgeHtml}
                                </div>
                                <h6 class="text-xs font-bold text-white group-hover:text-yellow-400 transition-colors truncate">${product.title}</h6>
                            </div>
                            <div class="text-right shrink-0">
                                ${product.original_price ? `<span class="text-[10px] text-gray-500 line-through block">${product.original_price}</span>` : ''}
                                <span class="text-xs font-black text-yellow-400">${product.price}</span>
                            </div>
                        </a>
                    `;
                });

                resultsContainer.innerHTML = html;
            } catch (err) {
                console.error('Live Search Error:', err);
                resultsContainer.innerHTML = `
                    <div class="p-4 text-center text-xs text-red-400">
                        Arama sırasında bir hata oluştu. Lütfen tekrar deneyin.
                    </div>
                `;
            }
        }, 250);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSearchModal();
        }
    });

    function openServiceTrackingModal(code = '') {
        const modal = document.getElementById('serviceTrackingModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        if (code) {
            document.getElementById('serviceTrackingCodeInput').value = code;
            handleServiceTrackingSubmit();
        }
    }

    function closeServiceTrackingModal() {
        const modal = document.getElementById('serviceTrackingModal');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    async function handleServiceTrackingSubmit(e) {
        if (e) e.preventDefault();
        const codeInput = document.getElementById('serviceTrackingCodeInput');
        const code = codeInput.value.trim();
        if (!code) return;

        const submitBtn = document.getElementById('serviceTrackingSubmitBtn');
        const loading = document.getElementById('serviceTrackingLoading');
        const errorBox = document.getElementById('serviceTrackingError');
        const resultBox = document.getElementById('serviceTrackingResult');

        errorBox.classList.add('hidden');
        resultBox.classList.add('hidden');
        loading.classList.remove('hidden');
        submitBtn.disabled = true;

        try {
            const response = await fetch(`{{ route('service-request.track') }}?tracking_code=${encodeURIComponent(code)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            loading.classList.add('hidden');
            submitBtn.disabled = false;

            if (!response.ok || !data.success) {
                errorBox.innerText = data.message || 'Girdiğiniz takip koduna ait servis kaydı bulunamadı.';
                errorBox.classList.remove('hidden');
                return;
            }

            // Bind values
            document.getElementById('stResultCode').innerText = data.tracking_code;
            document.getElementById('stResultDevice').innerText = data.device_model;
            document.getElementById('stResultType').innerText = data.service_type;
            document.getElementById('stResultDate').innerText = data.created_at_formatted;
            document.getElementById('stResultCustomer').innerHTML = `Müşteri: <span class="text-white font-bold">${data.customer_name}</span>`;
            document.getElementById('stResultIssue').innerText = data.issue_description;

            if (data.admin_note) {
                document.getElementById('stResultAdminNote').innerText = data.admin_note;
                document.getElementById('stAdminNoteBox').classList.remove('hidden');
            } else {
                document.getElementById('stAdminNoteBox').classList.add('hidden');
            }

            // Stepper styling
            const stage = data.stage;
            updateStepperStep('stStep1', stage >= 1, stage === 1);
            updateStepperStep('stStep2', stage >= 2, stage === 2);
            updateStepperStep('stStep3', stage >= 3, stage === 3);

            resultBox.classList.remove('hidden');

        } catch (err) {
            loading.classList.add('hidden');
            submitBtn.disabled = false;
            errorBox.innerText = 'Sorgulama yapılırken bir bağlantı hatası oluştu. Lütfen tekrar deneyin.';
            errorBox.classList.remove('hidden');
        }
    }

    function updateStepperStep(elementId, isReached, isCurrent) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const icon = el.querySelector('.step-icon');
        const label = el.querySelector('.step-label');

        if (isCurrent) {
            icon.className = 'w-10 h-10 rounded-full bg-yellow-500 border-2 border-yellow-300 text-afiDark flex items-center justify-center text-sm font-bold step-icon transition-all shadow-lg shadow-yellow-500/40 animate-pulse';
            label.className = 'text-[11px] font-bold text-yellow-400 step-label';
        } else if (isReached) {
            icon.className = 'w-10 h-10 rounded-full bg-green-500 border-2 border-green-400 text-white flex items-center justify-center text-sm font-bold step-icon transition-all';
            label.className = 'text-[11px] font-bold text-green-400 step-label';
        } else {
            icon.className = 'w-10 h-10 rounded-full bg-gray-800 border-2 border-gray-700 text-gray-500 flex items-center justify-center text-sm font-bold step-icon transition-all';
            label.className = 'text-[11px] font-bold text-gray-500 step-label';
        }
    }

    function openTrackModalWithCode(e, formEl) {
        e.preventDefault();
        const input = formEl.querySelector('input[name="tracking_code"]');
        if (input && input.value) {
            openServiceTrackingModal(input.value);
        }
    }



    /* ─── 1MINUS1 STAGGERED REVEAL SCROLL OBSERVER ─── */
    (function() {
        function initStaggeredReveal() {
            const revealItems = document.querySelectorAll('.scroll-reveal-item, .reveal-on-scroll');
            if (!revealItems.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const parent = el.parentElement;
                        const siblings = parent ? Array.from(parent.children).filter(c => c.classList.contains('scroll-reveal-item') || c.classList.contains('reveal-on-scroll')) : [];
                        const index = siblings.indexOf(el);
                        const delay = index >= 0 ? index * 0.08 : 0;
                        
                        el.style.transitionDelay = `${delay}s`;
                        el.classList.add('is-revealed');
                        el.classList.add('revealed');
                        observer.unobserve(el);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -40px 0px'
            });

            revealItems.forEach(item => observer.observe(item));
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initStaggeredReveal);
        } else {
            initStaggeredReveal();
        }
    })();

    /* Global Favori Toggle Yardımcısı */
    async function toggleFavorite(btn, productId) {
        if (!btn) return;
        const heartIcon = btn.querySelector('.fa-heart') || btn;
        
        try {
            const response = await fetch(`/favorites/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            // İkon Durumu Değiştirme
            if (data.isFavorited) {
                heartIcon.classList.remove('fa-regular', 'text-gray-400');
                heartIcon.classList.add('fa-solid', 'text-red-500', 'scale-125');
                setTimeout(() => heartIcon.classList.remove('scale-125'), 200);
                showToastMessage(data.message || 'Ürün favorilere eklendi!', 'success');
            } else {
                heartIcon.classList.remove('fa-solid', 'text-red-500');
                heartIcon.classList.add('fa-regular', 'text-gray-400');
                showToastMessage(data.message || 'Ürün favorilerden çıkarıldı.', 'info');
            }
        } catch (err) {
            console.error("Favori toggle hatası:", err);
        }
    }

    function showToastMessage(msg, type) {
        const toast = document.createElement('div');
        const bgClass = type === 'success' ? 'bg-red-500 text-white' : 'bg-gray-800 text-white border border-gray-700';
        const icon = type === 'success' ? 'fa-heart' : 'fa-info-circle';
        
        toast.className = `fixed bottom-24 right-5 ${bgClass} px-5 py-3 rounded-2xl shadow-2xl font-bold text-xs z-[99999] flex items-center gap-2.5 transition-all duration-300 translate-y-4 opacity-0`;
        toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${msg}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }
    </script>

    {{-- ════ MÜŞTERİ SİPARİŞ BAŞARI TOAST ════ --}}
    @if(session('success'))
    <div id="order-success-toast"
         style="position:fixed; bottom:2rem; right:2rem; z-index:99999; min-width:340px; max-width:440px;
                background:#fff; border:1.5px solid #d1fae5; border-radius:1.25rem;
                padding:1.25rem 1.5rem; box-shadow:0 25px 50px rgba(0,0,0,0.18);
                display:flex; gap:14px; align-items:flex-start;
                transform:translateY(30px); opacity:0; transition:all 0.45s cubic-bezier(0.16,1,0.3,1);">
        <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#10b981,#059669);
                    display:flex;align-items:center;justify-content:center;color:white;font-size:20px;flex-shrink:0;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <p style="font-weight:900;color:#064e3b;font-size:14px;margin:0 0 4px;line-height:1.3;">
                🎉 Siparişiniz Alındı!
            </p>
            <p style="font-size:12px;color:#047857;margin:0 0 8px;line-height:1.5;">
                {{ session('success') }}
            </p>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <span style="background:#ecfdf5;border:1px solid #6ee7b7;color:#065f46;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;">
                    <i class="fa-solid fa-envelope" style="margin-right:3px;"></i> Onay E-postası Simüle Edildi
                </span>
                <span style="background:#f0fdf4;border:1px solid #86efac;color:#166534;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;">
                    <i class="fa-solid fa-truck-fast" style="margin-right:3px;"></i> Kargo Takibi Aktif
                </span>
            </div>
        </div>
        <button onclick="document.getElementById('order-success-toast').remove()"
                style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:16px;padding:0;flex-shrink:0;margin-top:-2px;">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <script>
        requestAnimationFrame(function () {
            var t = document.getElementById('order-success-toast');
            if (!t) return;
            setTimeout(function () {
                t.style.opacity = '1';
                t.style.transform = 'translateY(0)';
            }, 200);
            setTimeout(function () {
                t.style.opacity = '0';
                t.style.transform = 'translateY(30px)';
                setTimeout(function () { if (t.parentNode) t.remove(); }, 450);
            }, 7000);
        });
    </script>
    @endif

    {{-- Chatbot Widget, Compare Bar & Cart Drawer --}}
    @include('components.chatbot-widget')
    @include('components.compare-bar')
    @include('components.cart-drawer')

    <script>
    /* ══════════════════════════════════════════════════════════════════
       AFI BİLİŞİM PREMİUM INTERACTIVE AMBIENT PARTICLE SYSTEM (60 FPS)
       ══════════════════════════════════════════════════════════════════ */
    (function () {
        const canvas = document.getElementById('afi-ambient-canvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let width = 0;
        let height = 0;
        let particles = [];
        let mouse = { x: null, y: null, radius: 140 };

        const isMobile = window.innerWidth < 768;
        const particleCount = isMobile ? 35 : 70;
        const maxConnectDist = isMobile ? 90 : 125;
        const colors = [
            'rgba(234, 179, 8, ',    // Afi Yellow #eab308
            'rgba(245, 158, 11, ',   // Amber #f59e0b
            'rgba(250, 204, 21, ',   // Light Gold #facc15
            'rgba(217, 119, 6, '     // Deep Amber #d97706
        ];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        window.addEventListener('resize', () => {
            resize();
            createParticles();
        });

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        class Particle {
            constructor() {
                this.reset();
            }

            reset() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.45;
                this.vy = (Math.random() - 0.5) * 0.45;
                this.radius = Math.random() * 1.8 + 0.8;
                this.colorPrefix = colors[Math.floor(Math.random() * colors.length)];
                this.baseAlpha = Math.random() * 0.45 + 0.25;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;

                if (mouse.x !== null && mouse.y !== null) {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const dist = Math.hypot(dx, dy);

                    if (dist < mouse.radius) {
                        const force = (mouse.radius - dist) / mouse.radius;
                        this.x -= (dx / dist) * force * 1.5;
                        this.y -= (dy / dist) * force * 1.5;
                    }
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.colorPrefix + this.baseAlpha + ')';
                ctx.shadowColor = '#eab308';
                ctx.shadowBlur = 6;
                ctx.fill();
                ctx.shadowBlur = 0;
            }
        }

        function createParticles() {
            particles = [];
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        function drawConstellationLines() {
            for (let a = 0; a < particles.length; a++) {
                for (let b = a + 1; b < particles.length; b++) {
                    const dx = particles[a].x - particles[b].x;
                    const dy = particles[a].y - particles[b].y;
                    const dist = Math.hypot(dx, dy);

                    if (dist < maxConnectDist) {
                        const alpha = (1 - dist / maxConnectDist) * 0.22;
                        ctx.beginPath();
                        ctx.moveTo(particles[a].x, particles[a].y);
                        ctx.lineTo(particles[b].x, particles[b].y);
                        ctx.strokeStyle = `rgba(234, 179, 8, ${alpha})`;
                        ctx.lineWidth = 0.65;
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();
            }

            drawConstellationLines();
            requestAnimationFrame(animate);
        }

        resize();
        createParticles();
        animate();
    })();
    </script>
</body>
</html>
