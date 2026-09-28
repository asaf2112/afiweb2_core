@extends('layouts.app')

@section('title', 'Afi Bilişim | Geleceğin Teknolojisi, Güvenli Çözümler')
@section('meta_description', 'Afi Bilişim — Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü bilgisayar ve güvenlik kamerası sistemleri. Uygun fiyat, hızlı kargo, Afi güvencesiyle.')
@section('og_title', 'Afi Bilişim | Geleceğin Teknolojisi, Güvenli Çözümler')
@section('og_description', 'Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü bilgisayar ve güvenlik kamerası sistemleri. Uygun fiyat, hızlı kargo.')
@section('og_url', route('home'))

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "Afi Bilişim",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "description": "Test edilmiş ikinci el ve sıfır bilgisayar, laptop, masaüstü bilgisayar ve güvenlik kamerası sistemleri.",
  "contactPoint": [{"@@type": "ContactPoint", "contactType": "customer support", "availableLanguage": "Turkish"}],
  "sameAs": []
}
</script>
@endpush

@section('content')
    <style>
        .product-card-hover:hover .product-img { transform: scale(1.05); }
        .perspective-1000 { perspective: 1000px; }
        .transform-style-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
        .flip-card.flipped .flip-inner { transform: rotateY(180deg); }
    </style>

    <!-- Flash İndirimler & Günün Fırsatları Section (Sayfa Başı Top Vitrin) -->
    @if(isset($flashSaleProducts) && $flashSaleProducts->count() > 0)
    <section class="relative z-10 pt-28 pb-14 bg-gradient-to-b from-[#0e0714] via-[#160b1c] to-[#0b0d13] overflow-hidden border-b border-red-900/50 shadow-2xl">
        <!-- Ambient Lighting Glows -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-red-600/20 rounded-full filter blur-[140px] pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/15 rounded-full filter blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-red-950/90 via-slate-900/95 to-amber-950/90 border border-red-500/50 rounded-3xl p-6 md:p-8 mb-10 shadow-2xl backdrop-blur-xl flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-center lg:text-left">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-tr from-red-600 via-amber-500 to-yellow-400 flex items-center justify-center text-slate-950 text-2xl md:text-3xl font-black shadow-lg shadow-red-500/40 shrink-0 animate-bounce">
                        <i class="fa-solid fa-bolt text-red-950"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-red-500/20 border border-red-500/50 text-red-300 text-xs font-black uppercase tracking-widest mb-1 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span> Canlı Fırsat Vitrini
                        </div>
                        <h2 class="font-heading text-3xl md:text-4xl font-black text-white tracking-tight">
                            ⚡ Flash İndirimler & Günün Fırsatları
                        </h2>
                        <p class="text-slate-300 text-sm md:text-base mt-1 font-medium">Sınırlı süre ve özel stoklarla sunulan dev donanım fırsatları!</p>
                    </div>
                </div>

                <!-- Global Countdown Showcase -->
                @php
                    $firstProduct = $flashSaleProducts->first();
                    $firstEnd = $firstProduct ? $firstProduct->discount_end_date : null;
                    $firstEndTimestamp = $firstEnd ? \Carbon\Carbon::parse($firstEnd)->timestamp * 1000 : \Carbon\Carbon::now()->addDays(2)->timestamp * 1000;
                @endphp
                <div class="bg-slate-950/90 border border-red-500/50 rounded-2xl p-4 md:px-6 flex items-center gap-3 shadow-inner shrink-0" 
                     id="main-flash-countdown-container" 
                     data-end-timestamp="{{ $firstEndTimestamp }}">
                    <div class="text-xs uppercase font-extrabold text-amber-400 tracking-wider text-center mr-1">
                        <i class="fa-solid fa-clock text-base block mb-0.5 animate-spin-slow"></i> Kalan Süre
                    </div>
                    <div class="flex items-center gap-2 font-mono font-black text-xl md:text-2xl text-white">
                        <div class="bg-red-950/90 border border-red-500/50 px-3 py-1.5 rounded-xl text-center min-w-[48px] shadow-sm">
                            <span id="main-cd-days">00</span>
                            <span class="block text-[9px] font-sans font-medium text-slate-400 uppercase tracking-normal">Gün</span>
                        </div>
                        <span class="text-red-500 animate-pulse">:</span>
                        <div class="bg-red-950/90 border border-red-500/50 px-3 py-1.5 rounded-xl text-center min-w-[48px] shadow-sm">
                            <span id="main-cd-hours">00</span>
                            <span class="block text-[9px] font-sans font-medium text-slate-400 uppercase tracking-normal">Saat</span>
                        </div>
                        <span class="text-red-500 animate-pulse">:</span>
                        <div class="bg-red-950/90 border border-red-500/50 px-3 py-1.5 rounded-xl text-center min-w-[48px] shadow-sm">
                            <span id="main-cd-mins">00</span>
                            <span class="block text-[9px] font-sans font-medium text-slate-400 uppercase tracking-normal">Dakika</span>
                        </div>
                        <span class="text-red-500 animate-pulse">:</span>
                        <div class="bg-red-950/90 border border-red-500/50 px-3 py-1.5 rounded-xl text-center min-w-[48px] shadow-sm">
                            <span id="main-cd-secs">00</span>
                            <span class="block text-[9px] font-sans font-medium text-slate-400 uppercase tracking-normal">Saniye</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($flashSaleProducts as $product)
                    @php
                        $origPrice = (float) $product->price;
                        $discPrice = (float) $product->discount_price;
                        $saveAmount = max(0, $origPrice - $discPrice);
                        $percDiscount = $origPrice > 0 ? round(($saveAmount / $origPrice) * 100) : 0;
                        $endTimestamp = $product->discount_end_date ? \Carbon\Carbon::parse($product->discount_end_date)->timestamp * 1000 : 0;
                    @endphp
                    <div class="bg-slate-900/95 border border-red-500/40 hover:border-amber-400 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-red-500/20 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col group relative">
                        <!-- Discount Percent Badge -->
                        <div class="absolute top-3 left-3 z-20 bg-gradient-to-r from-red-600 via-amber-500 to-yellow-400 text-slate-950 font-black text-xs px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-1">
                            <i class="fa-solid fa-fire text-red-950"></i> %{{ $percDiscount }} İNDİRİM
                        </div>

                        <!-- Single Product Live Countdown Badge -->
                        <div class="absolute top-3 right-3 z-20 bg-slate-950/90 border border-red-500/50 text-amber-300 font-mono text-[11px] font-bold px-2.5 py-1 rounded-xl shadow-md flex items-center gap-1.5 product-live-countdown"
                             data-end-timestamp="{{ $endTimestamp }}" id="prod-cd-{{ $product->id }}">
                            <i class="fa-regular fa-clock text-red-400"></i> <span class="cd-text">Yükleniyor...</span>
                        </div>

                        <!-- Product Image -->
                        <a href="{{ route('products.show', $product->slug) }}" class="relative block pt-[75%] bg-slate-950 overflow-hidden group">
                            <img src="{{ $product->main_image ? asset($product->main_image) : 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80' }}" 
                                 alt="{{ $product->title }}" 
                                 class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                        </a>

                        <!-- Product Info -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-xs text-amber-400 font-semibold mb-1 uppercase tracking-wider">
                                    {{ $product->category->name ?? 'Donanım' }}
                                </div>
                                <h3 class="font-bold text-white text-base line-clamp-2 mb-3 group-hover:text-amber-300 transition-colors">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
                                </h3>
                            </div>

                            <div>
                                <!-- Price Display -->
                                <div class="bg-slate-950/80 border border-slate-800 rounded-xl p-3 mb-4 shadow-inner">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-slate-400 line-through font-medium">{{ number_format($origPrice, 2, ',', '.') }} ₺</span>
                                        <span class="text-emerald-400 font-bold text-[11px] bg-emerald-950/90 px-2 py-0.5 rounded border border-emerald-500/40">
                                            {{ number_format($saveAmount, 0, ',', '.') }} ₺ Kazanç
                                        </span>
                                    </div>
                                    <div class="text-2xl font-black text-amber-400 tracking-tight">
                                        {{ number_format($discPrice, 2, ',', '.') }} <span class="text-sm font-semibold text-amber-300">₺</span>
                                    </div>
                                </div>

                                <!-- Limited Stock Indicator -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-[11px] text-slate-400 font-medium mb-1">
                                        <span>Fırsat Stoğu</span>
                                        <span class="text-amber-400 font-bold">Son {{ $product->stock }} Adet!</span>
                                    </div>
                                    <div class="w-full bg-slate-950 h-2 rounded-full overflow-hidden border border-slate-800">
                                        <div class="bg-gradient-to-r from-red-600 via-amber-500 to-yellow-400 h-full rounded-full animate-pulse" style="width: {{ min(100, max(20, $product->stock * 15)) }}%;"></div>
                                    </div>
                                </div>

                                <!-- Add to Cart Button -->
                                <button onclick="addToCart({{ $product->id }}, 1)" 
                                        class="w-full bg-gradient-to-r from-red-600 via-amber-500 to-yellow-400 hover:from-red-500 hover:to-yellow-300 text-slate-950 font-black py-3 px-4 rounded-xl shadow-lg shadow-red-600/20 hover:shadow-amber-400/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-sm cursor-pointer">
                                    <i class="fa-solid fa-cart-shopping"></i> Sepete Ekle
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Hero Section -->
    <header class="relative z-10 pt-6 pb-10 md:pt-8 md:pb-12 overflow-hidden border-b border-gray-800/80 w-full">
        <!-- En Üst Odak Alanı: Hızlı Kısayol Butonları -->
        <div class="max-w-7xl mx-auto px-4 text-center mb-6 lg:mb-8 w-full relative z-10">
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 w-full">
                <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-300 hover:to-yellow-300 text-slate-950 font-black px-6 sm:px-7 py-3 sm:py-3.5 rounded-full text-xs md:text-sm hover:scale-105 transition-all duration-300 shadow-xl shadow-amber-500/20 flex items-center gap-2.5">
                    <i class="fa-solid fa-layer-group"></i> Tüm Ürünleri Keşfet
                </a>
                <button type="button" onclick="openSearchModal()" class="bg-slate-900/80 hover:bg-slate-800 text-white border border-slate-700/80 hover:border-yellow-400/80 font-bold px-6 sm:px-7 py-3 sm:py-3.5 rounded-full text-xs md:text-sm hover:scale-105 transition-all duration-300 backdrop-blur-md flex items-center gap-2.5 cursor-pointer shadow-lg">
                    <i class="fa-solid fa-magnifying-glass text-yellow-400"></i> Hızlı Ürün Ara
                </button>
            </div>
        </div>

        <!-- E-Ticaret Tam Uyumlu Dinamik Database-Driven Slider / Kayan Banner Alanı (Carousel) -->
        @php
            $activeBanners = (isset($banners) && $banners->count() > 0) ? $banners : collect();
            $totalSlideCount = $activeBanners->count();
        @endphp

        @if($totalSlideCount > 0)
        <div id="hero-floating-content" class="w-full px-[5px] mx-auto transition-transform duration-75 ease-out will-change-transform relative z-10">
            <div x-data="{
                    currentSlide: 0,
                    totalSlides: {{ $totalSlideCount }},
                    autoplayTimer: null,
                    isPaused: false,
                    touchStartX: 0,
                    touchEndX: 0,
                    init() {
                        this.startAutoplay();
                    },
                    next() {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    },
                    prev() {
                        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    },
                    goTo(index) {
                        this.currentSlide = index;
                    },
                    startAutoplay() {
                        this.stopAutoplay();
                        this.autoplayTimer = setInterval(() => {
                            if (!this.isPaused) this.next();
                        }, 5000);
                    },
                    stopAutoplay() {
                        if (this.autoplayTimer) clearInterval(this.autoplayTimer);
                    },
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        if (this.touchStartX - this.touchEndX > 40) this.next();
                        if (this.touchEndX - this.touchStartX > 40) this.prev();
                    }
                 }"
                 @mouseenter="isPaused = true"
                 @mouseleave="isPaused = false"
                 @touchstart="handleTouchStart($event)"
                 @touchend="handleTouchEnd($event)"
                 class="relative w-full rounded-2xl sm:rounded-3xl overflow-hidden border border-amber-500/30 bg-[#0d111a] shadow-2xl group min-h-[460px] sm:min-h-[480px] md:min-h-[500px] lg:min-h-[540px] xl:min-h-[580px] flex flex-col justify-between text-left">

                <!-- Autoplay Progress Top Bar -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-slate-900/80 z-30 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-600 transition-all duration-300"
                         :style="'width: ' + ((currentSlide + 1) / totalSlides * 100) + '%'"></div>
                </div>

                <!-- Navigation Arrow Left -->
                <button type="button" 
                        @click="prev()" 
                        class="absolute left-1.5 sm:left-3 md:left-5 top-1/2 -translate-y-1/2 z-30 w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-xl sm:rounded-2xl bg-slate-950/75 hover:bg-amber-400 text-white hover:text-slate-950 border border-amber-500/30 hover:border-amber-400 backdrop-blur-md flex items-center justify-center transition-all duration-300 shadow-xl opacity-75 hover:opacity-100 hover:scale-110 cursor-pointer"
                        aria-label="Önceki Slayt">
                    <i class="fa-solid fa-chevron-left text-xs sm:text-sm md:text-base"></i>
                </button>

                <!-- Navigation Arrow Right -->
                <button type="button" 
                        @click="next()" 
                        class="absolute right-1.5 sm:right-3 md:right-5 top-1/2 -translate-y-1/2 z-30 w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-xl sm:rounded-2xl bg-slate-950/75 hover:bg-amber-400 text-white hover:text-slate-950 border border-amber-500/30 hover:border-amber-400 backdrop-blur-md flex items-center justify-center transition-all duration-300 shadow-xl opacity-75 hover:opacity-100 hover:scale-110 cursor-pointer"
                        aria-label="Sonraki Slayt">
                    <i class="fa-solid fa-chevron-right text-xs sm:text-sm md:text-base"></i>
                </button>

                <!-- Dynamic Slides Track Container -->
                <div class="relative w-full h-full min-h-[460px] sm:min-h-[480px] md:min-h-[500px] lg:min-h-[540px] xl:min-h-[580px] flex-1">
                    @foreach($activeBanners as $slideIndex => $banner)
                        <div x-show="currentSlide === {{ $slideIndex }}"
                             x-transition:enter="transition ease-out duration-500 transform"
                             x-transition:enter-start="opacity-0 translate-x-12 scale-98"
                             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                             x-transition:leave="transition ease-in duration-300 transform absolute inset-0"
                             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-x-12 scale-98"
                             class="absolute inset-0 p-4 sm:p-7 md:p-10 lg:p-12 xl:p-16 bg-gradient-to-br {{ $banner->bg_gradient ?? 'from-[#1a1103] via-[#140d02] to-[#090b10]' }} flex items-center overflow-hidden">
                            
                            <!-- Ambient Glow Pill -->
                            <div class="absolute -right-16 -top-16 w-96 h-96 
                                @if($banner->glow_color == 'cyan') bg-cyan-500/20
                                @elseif($banner->glow_color == 'blue') bg-blue-500/20
                                @elseif($banner->glow_color == 'red') bg-red-600/20
                                @elseif($banner->glow_color == 'emerald') bg-emerald-500/20
                                @elseif($banner->glow_color == 'purple') bg-purple-600/20
                                @else bg-amber-500/20 @endif rounded-full filter blur-[100px] pointer-events-none"></div>
                            
                            <div class="absolute -left-16 -bottom-16 w-80 h-80 
                                @if($banner->glow_color == 'cyan') bg-teal-600/15
                                @elseif($banner->glow_color == 'blue') bg-indigo-600/15
                                @elseif($banner->glow_color == 'red') bg-amber-500/15
                                @elseif($banner->glow_color == 'emerald') bg-purple-600/15
                                @else bg-red-600/15 @endif rounded-full filter blur-[90px] pointer-events-none"></div>
                            
                            <!-- Watermark Graphic -->
                            @if($banner->watermark_text)
                                <div class="absolute right-6 bottom-2 text-white/5 font-black text-6xl sm:text-8xl md:text-9xl tracking-tighter select-none font-heading pointer-events-none">
                                    {{ $banner->watermark_text }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 xl:gap-14 items-center w-full max-w-7xl 2xl:max-w-[1600px] mx-auto relative z-10">
                                <div class="lg:col-span-7 xl:col-span-7 space-y-3.5 sm:space-y-4 md:space-y-5 px-7 sm:px-10 lg:px-0">
                                    @if($banner->top_badge_text)
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full bg-slate-900/90 border text-[10px] sm:text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm
                                                @if($banner->top_badge_color == 'cyan') border-cyan-500/50 text-cyan-300
                                                @elseif($banner->top_badge_color == 'blue') border-blue-500/50 text-blue-300
                                                @elseif($banner->top_badge_color == 'red') border-red-500/50 text-red-300
                                                @elseif($banner->top_badge_color == 'emerald') border-emerald-500/50 text-emerald-300
                                                @else border-amber-500/50 text-amber-300 @endif">
                                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full animate-ping
                                                    @if($banner->top_badge_color == 'cyan') bg-cyan-400
                                                    @elseif($banner->top_badge_color == 'blue') bg-blue-400
                                                    @elseif($banner->top_badge_color == 'red') bg-red-400
                                                    @elseif($banner->top_badge_color == 'emerald') bg-emerald-400
                                                    @else bg-amber-400 @endif"></span> 
                                                <i class="fa-solid {{ $banner->top_badge_icon ?? 'fa-star' }} text-amber-400 mr-0.5 sm:mr-1"></i>
                                                {{ $banner->top_badge_text }}
                                            </span>
                                        </div>
                                    @endif

                                    <h2 class="font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight">
                                        {{ $banner->title }}<br>
                                        @if($banner->subtitle)
                                            <span class="
                                                @if($banner->glow_color == 'cyan') text-cyan-400
                                                @elseif($banner->glow_color == 'blue') text-blue-400
                                                @elseif($banner->glow_color == 'red') text-red-400
                                                @elseif($banner->glow_color == 'emerald') text-emerald-400
                                                @else text-amber-400 @endif">
                                                {{ $banner->subtitle }}
                                            </span>
                                        @endif
                                    </h2>

                                    @if($banner->description)
                                        <p class="text-slate-300 text-xs sm:text-sm md:text-base max-w-2xl sm:max-w-3xl leading-relaxed font-medium line-clamp-3 sm:line-clamp-none">
                                            {{ $banner->description }}
                                        </p>
                                    @endif

                                    <!-- Tag Badge Features -->
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-3 text-[10px] sm:text-xs font-bold text-slate-300 pt-1">
                                        @if($banner->tag1_text)
                                            <span class="flex items-center gap-1 sm:gap-1.5 text-amber-400 bg-amber-500/10 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-amber-500/20">
                                                <i class="{{ $banner->tag1_icon ?? 'fa-solid fa-check' }}"></i> {{ $banner->tag1_text }}
                                            </span>
                                        @endif
                                        @if($banner->tag2_text)
                                            <span class="flex items-center gap-1 sm:gap-1.5 text-emerald-400 bg-emerald-500/10 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-emerald-500/20">
                                                <i class="{{ $banner->tag2_icon ?? 'fa-solid fa-check' }}"></i> {{ $banner->tag2_text }}
                                            </span>
                                        @endif
                                        @if($banner->tag3_text)
                                            <span class="flex items-center gap-1 sm:gap-1.5 text-cyan-400 bg-cyan-500/10 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-cyan-500/20">
                                                <i class="{{ $banner->tag3_icon ?? 'fa-solid fa-check' }}"></i> {{ $banner->tag3_text }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Left Main CTA Button -->
                                    <div class="pt-2 sm:pt-4">
                                        <a href="{{ $banner->button_url ?? route('products.index') }}" 
                                           class="inline-flex items-center gap-2 sm:gap-2.5 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-300 hover:to-yellow-300 text-slate-950 font-black px-5 sm:px-7 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl text-xs sm:text-sm shadow-xl shadow-amber-500/30 hover:scale-105 transition-all">
                                            <span>{{ $banner->button_text ?? 'Keşfet' }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Right Side Visual Product Card Decorative WITH DIRECT "Ürüne Git" BUTTON (BÜYÜTÜLMÜŞ & GELİŞTİRİLMİŞ) -->
                                @if($banner->card_title)
                                    <div class="hidden lg:flex lg:col-span-5 xl:col-span-5 justify-end">
                                        <div class="bg-slate-900/95 border-2 border-amber-500/40 hover:border-amber-400/80 rounded-3xl p-6 sm:p-7 xl:p-8 2xl:p-9 shadow-2xl backdrop-blur-2xl space-y-4 xl:space-y-5 w-full max-w-md xl:max-w-lg 2xl:max-w-xl transform hover:scale-[1.02] transition-all duration-300 group/card relative overflow-hidden">
                                            <!-- Ambient Glow inside Card -->
                                            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                                            <!-- Card Header & Badge -->
                                            <div class="flex items-center justify-between gap-3 border-b border-slate-800/80 pb-3">
                                                <span class="text-xs sm:text-sm font-black text-amber-400 uppercase tracking-wider flex items-center gap-2">
                                                    <i class="fa-solid fa-microchip text-sm sm:text-base text-amber-400"></i> {{ $banner->card_header ?? 'AFI KAMPANYA' }}
                                                </span>
                                                @if($banner->card_badge)
                                                    <span class="text-xs font-black bg-gradient-to-r from-red-600 to-rose-600 text-white px-3 py-1 rounded-full uppercase tracking-wider shadow-md shadow-red-600/30">
                                                        {{ $banner->card_badge }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Optional Image if uploaded -->
                                            @if($banner->image_path)
                                                <div class="relative w-full h-44 sm:h-52 bg-slate-950/70 rounded-2xl overflow-hidden p-3 border border-slate-800 flex items-center justify-center">
                                                    <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->card_title }}" class="max-h-full max-w-full object-contain group-hover/card:scale-105 transition-transform duration-300">
                                                </div>
                                            @endif

                                            <!-- Card Product Title -->
                                            <div class="text-white font-extrabold text-xl sm:text-2xl xl:text-3xl leading-snug group-hover/card:text-amber-300 transition-colors">
                                                {{ $banner->card_title }}
                                            </div>

                                            <!-- Card Specs List -->
                                            <div class="space-y-2 sm:space-y-2.5 py-1">
                                                @if($banner->card_spec1) 
                                                    <div class="text-xs sm:text-sm xl:text-base text-slate-300 font-medium flex items-center gap-2.5">
                                                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0 shadow-sm shadow-amber-400/50"></span>
                                                        <span>{{ ltrim($banner->card_spec1, '• ') }}</span>
                                                    </div> 
                                                @endif
                                                @if($banner->card_spec2) 
                                                    <div class="text-xs sm:text-sm xl:text-base text-slate-300 font-medium flex items-center gap-2.5">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0 shadow-sm shadow-emerald-400/50"></span>
                                                        <span>{{ ltrim($banner->card_spec2, '• ') }}</span>
                                                    </div> 
                                                @endif
                                                @if($banner->card_spec3) 
                                                    <div class="text-xs sm:text-sm xl:text-base text-slate-300 font-medium flex items-center gap-2.5">
                                                        <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0 shadow-sm shadow-cyan-400/50"></span>
                                                        <span>{{ ltrim($banner->card_spec3, '• ') }}</span>
                                                    </div> 
                                                @endif
                                            </div>

                                            <!-- Card Price & Action Button Row -->
                                            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between gap-4">
                                                <div>
                                                    @if($banner->card_old_price)
                                                        <span class="block text-xs sm:text-sm text-slate-400 line-through font-medium">{{ $banner->card_old_price }}</span>
                                                    @endif
                                                    <span class="text-2xl sm:text-3xl xl:text-4xl font-black text-amber-400 tracking-tight">{{ $banner->card_price ?? 'Detaylar' }}</span>
                                                </div>

                                                <!-- KULLANICI İSTEĞİ: "Ürüne Git" Butonu -->
                                                <a href="{{ $banner->card_button_url ?? $banner->button_url ?? route('products.index') }}" 
                                                   class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-300 hover:to-yellow-300 text-slate-950 text-xs sm:text-sm xl:text-base font-black shadow-xl shadow-amber-500/25 hover:scale-105 transition-all cursor-pointer shrink-0">
                                                    <span>{{ $banner->card_button_text ?? 'Ürüne Git' }}</span>
                                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Dots Indicators -->
                <div class="absolute bottom-2.5 sm:bottom-4 md:bottom-5 left-1/2 -translate-x-1/2 z-30 flex items-center gap-1.5 sm:gap-2 bg-slate-950/80 border border-amber-500/30 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full backdrop-blur-md shadow-xl">
                    <template x-for="(slideIndex, index) in totalSlides" :key="index">
                        <button type="button" 
                                @click="goTo(index)" 
                                class="h-2 sm:h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === index ? 'w-6 sm:w-8 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 shadow-md shadow-amber-500/50' : 'w-2 sm:w-2.5 bg-slate-600/80 hover:bg-slate-400'"
                                :aria-label="'Slayt ' + (index + 1)">
                        </button>
                    </template>
                </div>
            </div>
        </div>
        @endif
    </header>

    <!-- Haftanın Fırsatları / Öne Çıkanlar Vitrini -->
    <section class="relative z-10 py-24 bg-[#0b0f19] overflow-hidden border-b border-gray-800/80">
        <!-- 1minus1 Tarzı Şık Ambient Glow Parıltıları -->
        <div class="absolute -left-32 top-1/4 w-96 h-96 bg-yellow-500/12 rounded-full filter blur-[120px] pointer-events-none"></div>
        <div class="absolute -right-32 bottom-1/4 w-96 h-96 bg-emerald-500/12 rounded-full filter blur-[120px] pointer-events-none"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full filter blur-[140px] pointer-events-none"></div>

        <!-- Öne Çıkanlar Özel Animasyonlu Donanım & Monitör Tuvali -->
        <canvas id="featured-hardware-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-80"></canvas>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="flex flex-col md:flex-row justify-between md:items-end mb-12 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold mb-3">
                        <i class="fa-solid fa-bolt text-yellow-400"></i> HAFTANIN ÖNE ÇIKANLARI
                    </div>
                    <h2 class="font-heading text-3xl md:text-4xl font-black text-white tracking-tight">⚡ Haftanın Fırsatları & Öne Çıkanlar</h2>
                    <p class="text-gray-400 text-sm mt-1">Sizin için özenle seçtiğimiz sıfır ve garantili ikinci el yüksek performanslı donanımlar.</p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-yellow-400 font-extrabold hover:text-yellow-300 transition text-sm shrink-0">
                    <span>Tüm Öne Çıkanları Gör</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full">
                @forelse($featuredProducts as $product)
                    <div class="scroll-reveal-item w-full">
                        @include('products._demo_card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                        <p class="text-gray-400 text-base font-medium">Henüz öne çıkan ürün bulunmamaktadır.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- 🏆 DİNAMİK ÇOK SATANLAR VE HAFTANIN ÜRÜNLERİ VİTRİNİ -->
    <section class="relative z-10 py-20 bg-[#070a12] border-b border-gray-800/80 overflow-hidden" x-data="{ activeTab: 'bestsellers' }">
        <!-- Ambient Glows -->
        <div class="absolute left-1/4 top-1/3 w-96 h-96 bg-amber-500/10 rounded-full filter blur-[150px] pointer-events-none"></div>
        <div class="absolute right-1/4 bottom-1/4 w-96 h-96 bg-indigo-600/10 rounded-full filter blur-[150px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Header & Tab Navigation -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-12 gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold mb-3">
                        <i class="fa-solid fa-trophy text-yellow-400"></i> ÖZEL KAMPANYALAR & SEÇKİLER
                    </div>
                    <h2 class="font-heading text-3xl md:text-4xl font-black text-white tracking-tight">🏆 Çok Satanlar & Haftanın Fırsatları</h2>
                    <p class="text-gray-400 text-sm mt-1">Müşterilerimizin en çok tercih ettiği ürünler ve bu haftaya özel öne çıkan fırsat ürünleri.</p>
                </div>

                <!-- Tab Switcher Buttons -->
                <div class="inline-flex p-1.5 rounded-2xl bg-slate-900/90 border border-slate-800 backdrop-blur-md shrink-0">
                    <button @click="activeTab = 'bestsellers'" 
                            :class="activeTab === 'bestsellers' ? 'bg-gradient-to-r from-amber-500 to-yellow-500 text-afiDark shadow-lg shadow-amber-500/20 font-black' : 'text-gray-400 hover:text-white font-bold'"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm transition-all duration-300">
                        <i class="fa-solid fa-crown text-amber-900" x-show="activeTab === 'bestsellers'"></i>
                        <i class="fa-solid fa-fire text-amber-500" x-show="activeTab !== 'bestsellers'"></i>
                        <span>En Çok Satanlar</span>
                        <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-black/20 text-current font-bold">{{ count($bestsellers) }}</span>
                    </button>

                    <button @click="activeTab = 'weekly'" 
                            :class="activeTab === 'weekly' ? 'bg-gradient-to-r from-amber-500 to-yellow-500 text-afiDark shadow-lg shadow-amber-500/20 font-black' : 'text-gray-400 hover:text-white font-bold'"
                            class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm transition-all duration-300">
                        <i class="fa-solid fa-bolt text-amber-900" x-show="activeTab === 'weekly'"></i>
                        <i class="fa-solid fa-star text-yellow-400" x-show="activeTab !== 'weekly'"></i>
                        <span>Haftanın Fırsatları</span>
                        <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-black/20 text-current font-bold">{{ count($weeklyDeals) }}</span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: En Çok Satanlar -->
            <div x-show="activeTab === 'bestsellers'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full">
                    @forelse($bestsellers as $product)
                        <div class="w-full relative group">
                            <!-- Bestseller Ribbon -->
                            <div class="absolute top-3 left-3 z-20 pointer-events-none">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-black font-black text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1 border border-amber-300/40">
                                    <i class="fa-solid fa-crown text-black"></i> Çok Satan
                                </span>
                            </div>
                            @include('products._demo_card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12">
                            <p class="text-gray-400 font-medium">Henüz çok satan ürün bulunmamaktadır.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 2: Haftanın Ürünleri -->
            <div x-show="activeTab === 'weekly'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full">
                    @forelse($weeklyDeals as $product)
                        <div class="w-full relative group">
                            <!-- Weekly Deal Ribbon -->
                            <div class="absolute top-3 left-3 z-20 pointer-events-none">
                                <span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-black text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1 border border-purple-400/40">
                                    <i class="fa-solid fa-star text-yellow-300"></i> Haftanın Fırsatı
                                </span>
                            </div>
                            @include('products._demo_card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12">
                            <p class="text-gray-400 font-medium">Henüz haftanın fırsat ürünü eklenmedi.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Popüler Ürünler Vitrini -->
    <section class="relative z-10 py-24 bg-[#0e121e] overflow-hidden border-b border-gray-800/80">
        <!-- Ambient Glows -->
        <div class="absolute right-0 top-1/4 w-96 h-96 bg-amber-500/10 rounded-full filter blur-[140px] pointer-events-none"></div>
        <div class="absolute left-10 bottom-10 w-96 h-96 bg-purple-600/10 rounded-full filter blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="flex flex-col md:flex-row justify-between md:items-end mb-12 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-bold mb-3">
                        <i class="fa-solid fa-fire text-orange-500"></i> EN ÇOK TERCİH EDİLENLER
                    </div>
                    <h2 class="font-heading text-3xl md:text-4xl font-black text-white tracking-tight">🔥 Popüler Ürünler</h2>
                    <p class="text-gray-400 text-sm mt-1">Teknoloji tutkunlarının en çok tercih ettiği popüler oyuncu sistemleri ve donanımlar.</p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-yellow-400 font-extrabold hover:text-yellow-300 transition text-sm shrink-0">
                    <span>Tüm Popüler Ürünleri Gör</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 w-full">
                @forelse($popularProducts as $product)
                    <div class="scroll-reveal-item w-full">
                        @include('products._demo_card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                        <p class="text-gray-400 text-base font-medium">Henüz popüler ürün bulunmamaktadır.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Hardware Showcase & Promotion Banners Section (1minus1 / Cyberpunk Style) -->
    <section class="relative z-10 py-20 bg-[#0b0f19] border-b border-gray-800/80 overflow-hidden">
        <!-- Ambient Backdrops -->
        <div class="absolute left-1/3 top-10 w-[500px] h-[300px] bg-red-600/10 rounded-full filter blur-[140px] pointer-events-none"></div>
        <div class="absolute right-1/4 bottom-10 w-[500px] h-[300px] bg-emerald-500/10 rounded-full filter blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Header Badge -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-bold mb-3">
                    <i class="fa-solid fa-microchip"></i> YENİ NESİL DONANIM EKOSİSTEMİ
                </div>
                <h2 class="font-heading text-3xl md:text-4xl font-black text-white tracking-tight">Gücünü Seç: AMD mi, NVIDIA mı?</h2>
                <p class="text-gray-400 text-sm mt-2">En yeni mimariler, 4K oyun performansı ve profesyonel iş istasyonu çözümleri stoklarımızda.</p>
            </div>

            <!-- Banner Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

                <!-- AMD Ryzen & Radeon Banner -->
                <div class="group relative bg-[#12151e] border border-red-500/30 hover:border-red-500/80 rounded-3xl p-8 lg:p-10 overflow-hidden transition-all duration-500 shadow-2xl hover:shadow-red-500/20 scroll-reveal-item flex flex-col justify-between min-h-[380px]">
                    <!-- Background Glow & Pattern -->
                    <div class="absolute -right-16 -top-16 w-80 h-80 bg-red-600/20 rounded-full filter blur-[80px] group-hover:scale-125 transition-transform duration-700 pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0e111a] via-[#12151e]/90 to-transparent z-0"></div>

                    <!-- Watermark Logo -->
                    <div class="absolute right-4 bottom-2 text-red-500/10 font-black text-8xl tracking-tighter select-none font-heading group-hover:text-red-500/20 transition-colors pointer-events-none">
                        AMD
                    </div>

                    <!-- Top Tags -->
                    <div class="relative z-10 flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 rounded-full bg-red-600 text-white text-[11px] font-black uppercase tracking-wider shadow-lg shadow-red-600/30">
                            AMD RYZEN™ 9000 & RADEON™
                        </span>
                        <span class="px-3 py-1 rounded-full bg-gray-900/80 border border-red-500/40 text-red-400 text-xs font-semibold">
                            3D V-Cache™
                        </span>
                    </div>

                    <!-- Main Content -->
                    <div class="relative z-10 mb-8">
                        <h3 class="font-heading text-3xl md:text-4xl font-black text-white group-hover:text-red-400 transition-colors leading-tight mb-4">
                            Zensational Performans & Kesintisiz Kare Hızları
                        </h3>
                        <p class="text-gray-300 text-sm max-w-md line-clamp-3 leading-relaxed">
                            AMD Ryzen™ 9000 serisi işlemciler ve Radeon™ RX 7000 serisi ekran kartları ile oyunlarda maksimum FPS ve rekabetçi üstünlük yakalayın.
                        </p>
                    </div>

                    <!-- CTA Button -->
                    <div class="relative z-10 flex items-center justify-between pt-6 border-t border-red-500/20">
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-red-500"></i> PCIe 5.0</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-red-500"></i> FSR 3.1</span>
                        </div>
                        <a href="{{ route('products.index') }}?search=AMD" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-500 hover:to-orange-500 text-white font-bold px-6 py-3 rounded-full text-xs transition-all shadow-lg shadow-red-600/30 group-hover:scale-105">
                            <span>AMD Ürünlerini Keşfet</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>

                <!-- NVIDIA GeForce RTX & Intel Banner -->
                <div class="group relative bg-[#12151e] border border-emerald-500/30 hover:border-emerald-500/80 rounded-3xl p-8 lg:p-10 overflow-hidden transition-all duration-500 shadow-2xl hover:shadow-emerald-500/20 scroll-reveal-item flex flex-col justify-between min-h-[380px]">
                    <!-- Background Glow & Pattern -->
                    <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-600/20 rounded-full filter blur-[80px] group-hover:scale-125 transition-transform duration-700 pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0e111a] via-[#12151e]/90 to-transparent z-0"></div>

                    <!-- Watermark Logo -->
                    <div class="absolute right-4 bottom-2 text-emerald-500/10 font-black text-8xl tracking-tighter select-none font-heading group-hover:text-emerald-500/20 transition-colors pointer-events-none">
                        RTX
                    </div>

                    <!-- Top Tags -->
                    <div class="relative z-10 flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 rounded-full bg-emerald-500 text-afiDark text-[11px] font-black uppercase tracking-wider shadow-lg shadow-emerald-500/30">
                            NVIDIA® GEFORCE RTX™ 40 & 50
                        </span>
                        <span class="px-3 py-1 rounded-full bg-gray-900/80 border border-emerald-500/40 text-emerald-400 text-xs font-semibold">
                            DLSS 3.5 AI
                        </span>
                    </div>

                    <!-- Main Content -->
                    <div class="relative z-10 mb-8">
                        <h3 class="font-heading text-3xl md:text-4xl font-black text-white group-hover:text-emerald-400 transition-colors leading-tight mb-4">
                            Gerçek Zamanlı Işın İzleme & Yapay Zeka Gücü
                        </h3>
                        <p class="text-gray-300 text-sm max-w-md line-clamp-3 leading-relaxed">
                            NVIDIA GeForce RTX™ 40 serisi Ada Lovelace & Blackwell mimarisi ile Full Path Tracing ve DLSS 3.5 kare oluşturma teknolojisinin keyfini çıkarın.
                        </p>
                    </div>

                    <!-- CTA Button -->
                    <div class="relative z-10 flex items-center justify-between pt-6 border-t border-emerald-500/20">
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400"></i> Ray Tracing</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400"></i> Reflex</span>
                        </div>
                        <a href="{{ route('products.index') }}?search=RTX" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-bold px-6 py-3 rounded-full text-xs transition-all shadow-lg shadow-emerald-500/30 group-hover:scale-105">
                            <span>GeForce RTX Kartları İncele</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom Full-Width Special Promotion Ribbon -->
            <div class="bg-gradient-to-r from-gray-900 via-[#161a23] to-gray-900 border border-yellow-500/30 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl relative overflow-hidden scroll-reveal-item">
                <div class="absolute -left-20 top-0 w-60 h-60 bg-yellow-500/10 rounded-full filter blur-[70px] pointer-events-none"></div>
                
                <div class="flex items-center gap-5 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-500/20 border border-yellow-500/40 text-yellow-400 flex items-center justify-center text-2xl shrink-0 shadow-lg shadow-yellow-500/20">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h4 class="font-heading text-xl font-bold text-white mb-1">Hayalindeki Oyuncu Bilgisayarını Kendin Topla!</h4>
                        <p class="text-gray-300 text-xs md:text-sm">Afi PC Sihirbazı ile tam uyumlu parçaları seçin, anında sepetinize ekleyin veya hazır oyuncu sistemlerini ve performans kasalarını inceleyin.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 relative z-10 w-full md:w-auto justify-end">
                    <a href="{{ route('pc-builder.index') }}" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold px-6 py-3 rounded-full text-xs transition-all shadow-lg shadow-yellow-500/20 flex items-center gap-2">
                        <i class="fa-solid fa-microchip"></i>
                        <span>PC Sihirbazını Başlat</span>
                    </a>
                    <a href="{{ url('/kategori/masaustu-bilgisayar?is_gaming=1') }}" class="bg-purple-600/20 hover:bg-purple-600 text-purple-400 hover:text-white border border-purple-500/40 font-bold px-6 py-3 rounded-full text-xs transition-all flex items-center gap-2 shadow-lg shadow-purple-900/30">
                        <i class="fa-solid fa-gamepad"></i>
                        <span>Oyuncu Bilgisayarları</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

<script>

/* ─── DYNAMIC FLASH SALE LIVE COUNTDOWN TIMER (ZERO-REFLOW & CACHED DOM) ─── */
(function() {
    let mainCDCache = { d: '', h: '', m: '', s: '' };
    let mainContainer = null;
    let dEl = null, hEl = null, mEl = null, sEl = null;
    let prodItems = [];

    function cacheElements() {
        mainContainer = document.getElementById('main-flash-countdown-container');
        if (mainContainer) {
            dEl = document.getElementById('main-cd-days');
            hEl = document.getElementById('main-cd-hours');
            mEl = document.getElementById('main-cd-mins');
            sEl = document.getElementById('main-cd-secs');
        }
        prodItems = Array.from(document.querySelectorAll('.product-live-countdown')).map(el => ({
            el: el,
            target: Number(el.getAttribute('data-end-timestamp') || 0),
            textEl: el.querySelector('.cd-text'),
            cachedText: ''
        }));
    }

    function updateFlashCountdowns() {
        const now = Date.now();

        if (mainContainer) {
            const mainTarget = Number(mainContainer.getAttribute('data-end-timestamp') || 0);
            if (mainTarget > 0) {
                const diff = mainTarget - now;
                let days = '00', hours = '00', minutes = '00', seconds = '00';

                if (diff > 0) {
                    days = String(Math.floor(diff / 86400000)).padStart(2, '0');
                    hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                    minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                    seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                }

                if (dEl && mainCDCache.d !== days) { dEl.textContent = days; mainCDCache.d = days; }
                if (hEl && mainCDCache.h !== hours) { hEl.textContent = hours; mainCDCache.h = hours; }
                if (mEl && mainCDCache.m !== minutes) { mEl.textContent = minutes; mainCDCache.m = minutes; }
                if (sEl && mainCDCache.s !== seconds) { sEl.textContent = seconds; mainCDCache.s = seconds; }
            }
        }

        for (let i = 0; i < prodItems.length; i++) {
            const item = prodItems[i];
            if (!item.target || !item.textEl) continue;
            const diff = item.target - now;
            let textFormatted = '';

            if (diff <= 0) {
                textFormatted = 'Süre Doldu';
            } else {
                const totalHours = Math.floor(diff / 3600000);
                const mins = Math.floor((diff % 3600000) / 60000);
                const secs = Math.floor((diff % 60000) / 1000);

                if (totalHours >= 24) {
                    const days = Math.floor(totalHours / 24);
                    textFormatted = `${days}g ${totalHours % 24}s ${String(mins).padStart(2, '0')}dk`;
                } else {
                    textFormatted = `${String(totalHours).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }
            }

            if (item.cachedText !== textFormatted) {
                item.textEl.textContent = textFormatted;
                item.cachedText = textFormatted;
                if (diff <= 0) {
                    item.el.classList.add('bg-gray-800', 'text-gray-400');
                }
            }
        }
    }

    function initTimer() {
        cacheElements();
        updateFlashCountdowns();
        setInterval(updateFlashCountdowns, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTimer);
    } else {
        initTimer();
    }
})();
</script>
@endsection


