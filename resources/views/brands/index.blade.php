@extends('layouts.app')

@section('title', 'Marka Katalogları | Dünyaca Ünlü Donanım & Bilgisayar Markaları - Afi Bilişim')
@section('meta_description', 'ASUS, MSI, Samsung, Corsair, Kingston ve daha fazlası. Resmi garantili hazır bilgisayarlar, ekran kartları, anakartlar, RAM ve SSD depolama birimleri Afi Bilişim’de.')

@section('content')
<div class="min-h-screen pt-28 pb-20 bg-gradient-to-b from-[#0b0f19] via-[#10141f] to-[#0b0f19] text-white">

    <!-- Breadcrumbs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <nav class="flex items-center gap-2 text-xs text-gray-400 font-medium">
            <a href="{{ route('home') }}" class="hover:text-yellow-400 transition flex items-center gap-1.5">
                <i class="fa-solid fa-house text-yellow-500"></i> Ana Sayfa
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] text-gray-600"></i>
            <span class="text-yellow-400 font-bold">Marka Katalogları</span>
        </nav>
    </div>

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-14 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-black uppercase tracking-wider mb-4 shadow-sm">
            <i class="fa-solid fa-award text-yellow-500"></i> Lider Teknoloji Üreticileri
        </div>
        <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
            Popüler <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-300 to-yellow-500">Marka Katalogları</span>
        </h1>
        <p class="text-gray-400 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            ASUS, MSI, Samsung, Corsair, Kingston ve küresel devlerin en güncel hazır bilgisayarlarını ve donanım bileşenlerini tek bir çatı altında inceleyin.
        </p>

        <!-- Quick Stats Banner -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto mt-10">
            <div class="bg-gray-900/60 border border-gray-800 rounded-2xl p-4 text-center backdrop-blur-md">
                <div class="text-2xl font-black text-yellow-400 font-heading">15+</div>
                <div class="text-xs text-gray-400 font-medium mt-0.5">Küresel Marka</div>
            </div>
            <div class="bg-gray-900/60 border border-gray-800 rounded-2xl p-4 text-center backdrop-blur-md">
                <div class="text-2xl font-black text-emerald-400 font-heading">%100</div>
                <div class="text-xs text-gray-400 font-medium mt-0.5">Orijinal & Garantili</div>
            </div>
            <div class="bg-gray-900/60 border border-gray-800 rounded-2xl p-4 text-center backdrop-blur-md">
                <div class="text-2xl font-black text-blue-400 font-heading">Tam Uyum</div>
                <div class="text-xs text-gray-400 font-medium mt-0.5">PC Sihirbazı Desteği</div>
            </div>
            <div class="bg-gray-900/60 border border-gray-800 rounded-2xl p-4 text-center backdrop-blur-md">
                <div class="text-2xl font-black text-purple-400 font-heading">Hızlı Kargo</div>
                <div class="text-xs text-gray-400 font-medium mt-0.5">Aynı Gün Sevkiyat</div>
            </div>
        </div>
    </div>

    <!-- Featured Brands Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-6 bg-yellow-500 rounded-full"></span>
                <h2 class="text-xl sm:text-2xl font-black text-white font-heading tracking-tight">Öne Çıkan Donanım & Bilgisayar Markaları</h2>
            </div>
            <span class="text-xs text-gray-400 font-semibold hidden sm:inline-block">Kataloğa gitmek için markayı seçin</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($brandsList as $b)
                <div class="group relative bg-[#121622] border border-gray-800 hover:border-yellow-500/50 rounded-3xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-yellow-500/10 flex flex-col justify-between">
                    
                    <!-- Ambient Glow -->
                    <div class="absolute -right-12 -top-12 w-40 h-40 bg-gradient-to-br {{ $b['logo_bg'] }} rounded-full opacity-20 filter blur-2xl group-hover:opacity-40 transition-opacity pointer-events-none"></div>

                    <div>
                        <!-- Header Banner -->
                        <div class="p-6 bg-gradient-to-br {{ $b['logo_bg'] }} relative overflow-hidden">
                            <div class="flex items-center justify-between gap-3 relative z-10">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center p-2 text-white text-2xl shadow-inner shrink-0 overflow-hidden">
                                        @if(!empty($b['logo']))
                                            <img src="{{ $b['logo'] }}" alt="{{ $b['name'] }}" class="max-h-full max-w-full object-contain filter drop-shadow">
                                        @else
                                            <i class="fa-solid {{ $b['icon'] }}"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-yellow-300 block">{{ $b['badge'] }}</span>
                                        <h3 class="text-2xl font-black text-white font-heading tracking-wide leading-none mt-0.5">{{ $b['name'] }}</h3>
                                    </div>
                                </div>
                                <span class="bg-black/40 backdrop-blur-md text-white font-mono font-bold text-xs px-3 py-1 rounded-full border border-white/10 shrink-0">
                                    {{ $b['count'] }} Ürün
                                </span>
                            </div>
                        </div>

                        <!-- Body Content -->
                        <div class="p-6 space-y-4">
                            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed font-normal min-h-[48px]">
                                {{ $b['description'] }}
                            </p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1.5 pt-2 border-t border-gray-800/80">
                                @foreach($b['tags'] as $tag)
                                    <span class="text-[10px] font-bold bg-gray-900 text-gray-400 border border-gray-800 px-2 py-0.5 rounded-md">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-6 pt-0 border-t border-gray-800/50 mt-4 flex items-center justify-between">
                        @if($b['min_price'] > 0)
                            <div>
                                <span class="text-[10px] text-gray-500 uppercase tracking-wider block font-medium">Başlangıç</span>
                                <span class="text-sm font-black text-yellow-400">{{ number_format($b['min_price'], 0, ',', '.') }} TL</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-500">Geniş Ürün Yelpazesi</span>
                        @endif

                        <a href="{{ route('brands.show', $b['slug']) }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-afiDark font-black px-4 py-2 rounded-xl text-xs transition-all shadow-md group-hover:scale-105 cursor-pointer">
                            <span>Kataloğu Aç</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Other Partner Brands Section -->
    @if(isset($otherBrands) && $otherBrands->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-gray-950/70 border border-gray-800/80 rounded-3xl p-6 sm:p-8 backdrop-blur-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center text-yellow-400 text-lg">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white font-heading">Diğer Güvenilir Donanım Markaları</h3>
                        <p class="text-xs text-gray-400">Cooler Master, NZXT, G.Skill, Crucial, Logitech ve daha fazlası</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                @foreach($otherBrands as $ob)
                    @php $obSlug = \Illuminate\Support\Str::slug($ob->brand); @endphp
                    <a href="{{ route('brands.show', $obSlug) }}" class="inline-flex items-center gap-2 bg-gray-900/90 hover:bg-yellow-500 hover:text-afiDark text-gray-300 border border-gray-800 hover:border-yellow-400 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all group shadow-sm">
                        <span>{{ $ob->brand }}</span>
                        <span class="bg-gray-800 group-hover:bg-black/20 text-yellow-400 group-hover:text-afiDark px-2 py-0.5 rounded-full text-[10px] font-black">
                            {{ $ob->count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Trust Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-yellow-500/10 via-amber-500/5 to-transparent border border-yellow-500/30 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-yellow-500/20 border border-yellow-500/40 text-yellow-400 flex items-center justify-center text-3xl shrink-0">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-bold text-white font-heading">Afi Bilişim Resmi Distribütör ve Garanti Güvencesi</h4>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 max-w-2xl">
                        Listelenen tüm teknoloji markalarının ürünleri kapalı kutu, faturalı ve 24 aya varan resmi distribütör garantisiyle satılmaktadır.
                    </p>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold px-6 py-3 rounded-full text-xs transition-all shadow-lg shadow-yellow-500/20 shrink-0">
                Tüm Ürün Kataloğunu Gör
            </a>
        </div>
    </div>

</div>
@endsection
