@extends('layouts.app')

@section('title', $brandName . ' Ürünleri ve Marka Kataloğu | Afi Bilişim')
@section('meta_description', $brandName . ' marka hazır bilgisayarlar, oyuncu kasaları, anakartlar, ekran kartları ve donanım bileşenleri Afi Bilişim güvencesiyle.')

@section('content')
<div class="min-h-screen pt-28 pb-20 bg-gradient-to-b from-[#0b0f19] via-[#10141f] to-[#0b0f19] text-white">

    <!-- Breadcrumbs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <nav class="flex items-center gap-2 text-xs text-gray-400 font-medium">
            <a href="{{ route('home') }}" class="hover:text-yellow-400 transition flex items-center gap-1.5">
                <i class="fa-solid fa-house text-yellow-500"></i> Ana Sayfa
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] text-gray-600"></i>
            <a href="{{ route('brands.index') }}" class="hover:text-yellow-400 transition">Markalar</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-gray-600"></i>
            <span class="text-yellow-400 font-bold">{{ $brandName }}</span>
        </nav>
    </div>

    <!-- Brand Hero Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="bg-gradient-to-r from-gray-950 via-[#151924] to-gray-950 border border-gray-800 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-yellow-500/10 rounded-full filter blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-start sm:items-center gap-4 sm:gap-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-yellow-500/15 border border-yellow-500/40 text-yellow-400 flex items-center justify-center text-3xl sm:text-4xl font-black font-heading shrink-0 shadow-lg shadow-yellow-500/10">
                        {{ substr($brandName, 0, 1) }}
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/15 border border-yellow-500/30 text-yellow-400 text-[11px] font-black uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-award"></i> Resmi Marka Kataloğu
                        </div>
                        <h1 class="font-heading text-2xl sm:text-4xl font-black text-white tracking-tight">
                            {{ $brandName }} <span class="text-gray-400 font-medium text-lg sm:text-2xl">Ürünleri & Donanımları</span>
                        </h1>
                        <p class="text-gray-300 text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed">
                            {{ $brandBio }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-gray-900/80 border border-gray-800 rounded-2xl px-5 py-4 shrink-0 self-start md:self-center">
                    <div class="text-right">
                        <div class="text-xs text-gray-400 font-medium">Toplam Ürün</div>
                        <div class="text-2xl font-black text-yellow-400 font-heading">{{ $totalBrandProducts }}</div>
                    </div>
                    <div class="w-px h-10 bg-gray-800"></div>
                    <a href="{{ route('pc-builder.index') }}" class="bg-yellow-500/10 hover:bg-yellow-500 hover:text-afiDark text-yellow-400 border border-yellow-500/30 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5" title="Bu marka ile sistem topla">
                        <i class="fa-solid fa-microchip"></i> PC Sihirbazı
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Tabs & Sorting Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 backdrop-blur-md">
            
            <!-- Category Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                <a href="{{ route('brands.show', $brandSlug) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !$activeCategory ? 'bg-yellow-500 text-afiDark shadow-md shadow-yellow-500/20' : 'bg-gray-900 text-gray-400 hover:text-white hover:bg-gray-800 border border-gray-800' }}">
                    Tümü ({{ $totalBrandProducts }})
                </a>

                @foreach($brandCategories as $cat)
                    @php 
                        $isCatActive = $activeCategory && $activeCategory->id === $cat->id; 
                        $catCount = \App\Models\Product::where('brand', 'LIKE', $brandName)->where('category_id', $cat->id)->count();
                    @endphp
                    <a href="{{ route('brands.show', ['brand' => $brandSlug, 'category' => $cat->slug]) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $isCatActive ? 'bg-yellow-500 text-afiDark shadow-md shadow-yellow-500/20' : 'bg-gray-900 text-gray-400 hover:text-white hover:bg-gray-800 border border-gray-800' }}">
                        {{ $cat->name }} ({{ $catCount }})
                    </a>
                @endforeach
            </div>

            <!-- Sorting Select -->
            <div class="flex items-center gap-3 shrink-0 self-end md:self-center">
                <span class="text-xs text-gray-400 font-bold whitespace-nowrap flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-down-wide-short text-yellow-500"></i> Sırala:
                </span>
                <select onchange="window.location.href = this.value" 
                        class="bg-gray-900 border border-gray-800 text-gray-200 text-xs font-semibold rounded-xl px-3 py-2 outline-none focus:border-yellow-400 cursor-pointer">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ $sort === 'latest' ? 'selected' : '' }}>En Yeni Eklenenler</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Fiyat: Düşükten Yükseğe</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Fiyat: Yüksekten Düşüğe</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @php
                        $img = $product->main_image 
                            ? (str_starts_with($product->main_image, 'http') ? $product->main_image : asset($product->main_image)) 
                            : 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400&q=80';
                        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                        $displayPrice = $hasDiscount ? $product->discount_price : $product->price;
                    @endphp

                    <div class="bg-gray-900/90 border border-gray-800 hover:border-yellow-500/50 rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-yellow-500/10 flex flex-col justify-between group">
                        <div>
                            <!-- Thumbnail Area -->
                            <div class="relative bg-black/40 p-4 aspect-square flex items-center justify-center overflow-hidden">
                                <a href="{{ route('products.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                    <img src="{{ $img }}" alt="{{ $product->title }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                </a>

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start">
                                    @if($product->badge)
                                        <span class="bg-yellow-500 text-afiDark text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm uppercase">
                                            {{ $product->badge }}
                                        </span>
                                    @endif
                                    @if($hasDiscount)
                                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-sm">
                                            İNDİRİM
                                        </span>
                                    @endif
                                </div>

                                <!-- Favorite Button -->
                                <button type="button" 
                                        onclick="toggleFavorite({{ $product->id }})" 
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-900/80 border border-gray-700 hover:bg-red-500 hover:text-white text-gray-400 flex items-center justify-center text-xs transition cursor-pointer"
                                        title="Favorilere Ekle">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>

                            <!-- Details -->
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between text-[11px] text-gray-500 font-bold uppercase tracking-wider">
                                    <span>{{ $product->category->name ?? 'Donanım' }}</span>
                                    <span class="{{ $product->stock > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $product->stock > 0 ? 'Stokta Var' : 'Tükendi' }}
                                    </span>
                                </div>

                                <h3 class="text-xs sm:text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-yellow-400 transition-colors">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->title }}
                                    </a>
                                </h3>

                                <!-- Hardware Specs Quick Chips -->
                                <div class="flex flex-wrap gap-1 pt-1">
                                    @if($product->effective_socket)
                                        <span class="text-[9px] font-bold bg-gray-800 text-yellow-400 px-1.5 py-0.5 rounded border border-gray-700">
                                            {{ $product->effective_socket }}
                                        </span>
                                    @endif
                                    @if($product->effective_ram_type)
                                        <span class="text-[9px] font-bold bg-gray-800 text-purple-300 px-1.5 py-0.5 rounded border border-gray-700">
                                            {{ $product->effective_ram_type }}
                                        </span>
                                    @endif
                                    @if($product->effective_tdp_watt > 0)
                                        <span class="text-[9px] font-bold bg-gray-800 text-amber-400 px-1.5 py-0.5 rounded border border-gray-700">
                                            {{ $product->effective_tdp_watt }}W
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Price and Action -->
                        <div class="p-4 pt-0 border-t border-gray-800/60 mt-3 flex items-center justify-between gap-3">
                            <div>
                                @if($hasDiscount)
                                    <div class="text-[10px] text-gray-500 line-through font-semibold">
                                        {{ number_format($product->price, 0, ',', '.') }} TL
                                    </div>
                                @endif
                                <div class="text-sm sm:text-base font-black text-yellow-400 font-heading">
                                    {{ number_format($displayPrice, 0, ',', '.') }} TL
                                </div>
                            </div>

                            <button type="button" 
                                    onclick="addToCart({{ $product->id }})" 
                                    class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-black px-3.5 py-2 rounded-xl text-xs transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                                <i class="fa-solid fa-cart-plus text-xs"></i>
                                <span class="hidden sm:inline">Sepete</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-gray-950/50 border border-gray-800 rounded-3xl p-16 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-500 text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Bu Kategori Altında Ürün Bulunamadı</h3>
                <p class="text-xs text-gray-400 mb-6">Seçtiğiniz filtreye uygun {{ $brandName }} ürünü şu an stokta bulunmuyor.</p>
                <a href="{{ route('brands.show', $brandSlug) }}" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold px-6 py-2.5 rounded-full text-xs transition inline-flex items-center gap-2">
                    Tüm {{ $brandName }} Ürünlerini Göster
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
