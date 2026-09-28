@extends('layouts.app')

@section('title', ($product->name ?? 'Ürün Detayı') . ' | Afi Bilişim')

@section('content')
<div class="bg-afiGray py-12">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-8 text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-yellow-600 transition">Ana Sayfa</a>
            <span class="mx-2"><i class="fa-solid fa-angle-right"></i></span>
            <a href="{{ route('products.index') }}" class="hover:text-yellow-600 transition">Ürünler</a>
            <span class="mx-2"><i class="fa-solid fa-angle-right"></i></span>
            <span class="text-afiDark font-semibold">{{ $product->name ?? 'Asus ROG Strix G16' }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            
            <!-- Left: Image Gallery -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-8 flex items-center justify-center h-[500px] border border-gray-100 relative group overflow-hidden">
                    <div class="absolute top-6 left-6 z-10 bg-yellow-500 text-afiDark text-xs font-black px-4 py-2 rounded-full uppercase tracking-wider shadow-md">
                        {{ ($product->condition_type ?? 'new') === 'new' ? 'Sıfır Kapalı Kutu' : 'İkinci El' }}
                    </div>
                    @if(isset($product) && $product->images && $product->images->where('is_main', true)->first())
                        <img src="{{ asset('storage/' . $product->images->where('is_main', true)->first()->image_path) }}" alt="{{ $product->name }}" class="object-contain w-full h-full transform group-hover:scale-110 transition-transform duration-700">
                    @else
                        <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=800&q=80" alt="{{ $product->name ?? 'Product' }}" class="object-contain w-full h-full transform group-hover:scale-110 transition-transform duration-700">
                    @endif
                </div>
                <!-- Thumbnail images could go here -->
                <div class="flex gap-4 overflow-x-auto pb-2">
                    <div class="w-24 h-24 rounded-xl bg-gray-50 border-2 border-yellow-500 cursor-pointer flex-shrink-0 p-2">
                        <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=200&q=80" alt="Thumb 1" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div class="w-24 h-24 rounded-xl bg-gray-50 border border-gray-200 hover:border-yellow-500 cursor-pointer transition flex-shrink-0 p-2">
                        <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=200&q=80" alt="Thumb 2" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div class="w-24 h-24 rounded-xl bg-gray-50 border border-gray-200 hover:border-yellow-500 cursor-pointer transition flex-shrink-0 p-2">
                        <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=200&q=80" alt="Thumb 3" class="w-full h-full object-cover rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="flex flex-col">
                <p class="text-yellow-600 font-bold uppercase tracking-wider text-sm mb-2">{{ $product->category->name ?? 'Bilgisayar' }}</p>
                <h1 class="text-4xl font-black text-afiDark mb-4 leading-tight">{{ $product->name ?? 'Asus ROG Strix G16 (2023)' }}</h1>
                
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-bold text-afiDark mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-yellow-500"></i> Kısa Özellikler
                    </h3>
                    <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $product->short_description ?: 'Kısa özellikler belirtilmemiş.' }}
                    </div>
                </div>

                @if($product->specs && count($product->specs) > 0)
                <div class="grid grid-cols-2 gap-4 mb-10">
                    @foreach($product->specs as $key => $value)
                    <div class="bg-white p-4 rounded-xl border border-gray-200 flex items-center gap-4 shadow-sm hover:border-yellow-400 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-gray-500 truncate">{{ $key }}</p>
                            <p class="font-bold text-afiDark text-sm truncate" title="{{ $value }}">{{ $value }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="flex text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="text-sm text-gray-500">(12 Değerlendirme)</span>
                    <span class="text-sm text-green-600 font-semibold bg-green-50 px-2 py-1 rounded"><i class="fa-solid fa-check-circle"></i> Stokta Var</span>
                </div>

                <div class="mb-8">
                    @if(isset($product) && $product->is_discount_active)
                        <div class="flex items-end gap-4">
                            <p class="text-3xl font-bold text-gray-400 line-through mb-1">{{ number_format($product->price ?? 42500, 2, ',', '.') }} ₺</p>
                            <p class="text-5xl font-black text-emerald-500">{{ number_format($product->discount_price, 2, ',', '.') }} ₺</p>
                        </div>
                    @else
                        <p class="text-5xl font-black text-afiDark">{{ number_format($product->price ?? 42500, 2, ',', '.') }} ₺</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-2">KDV Dahil, Ücretsiz Kargo</p>
                </div>

                <!-- Dynamic Specs are rendered above -->

                <!-- Action Buttons -->
                <div class="flex flex-col gap-4 mt-auto">
                    <div class="flex gap-4">
                        <a href="{{ route('cart.index') }}" class="flex-1 bg-yellow-500 text-afiDark text-xl font-black py-4 px-8 rounded-2xl shadow-xl shadow-yellow-500/30 hover:bg-yellow-400 hover:shadow-yellow-400/50 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 border-2 border-yellow-500">
                            <i class="fa-solid fa-cart-shopping text-2xl"></i> SEPETE EKLE
                        </a>
                        <button class="w-16 h-16 bg-afiDark text-white rounded-2xl flex items-center justify-center text-xl hover:bg-gray-800 transition shadow-lg shrink-0">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                    <button class="w-full bg-transparent text-afiDark text-sm font-bold py-3 px-6 rounded-xl border border-gray-300 hover:border-yellow-500 hover:text-yellow-600 transition-all flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg text-green-500"></i> WhatsApp Üzerinden Sipariş Ver (Geçici)
                    </button>
                </div>

            </div>
        </div>
        
        <!-- Alt Sekmeler (Açıklama & Yorumlar) -->
        <div class="mt-16 bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-afiDark mb-6 border-b border-gray-100 pb-4">Detaylı Açıklama</h2>
            <div class="prose max-w-none text-gray-600 whitespace-pre-line leading-relaxed">
                {{ $product->description ?: 'Bu ürün için henüz detaylı bir açıklama girilmemiştir.' }}
            </div>
        </div>

    </div>
</div>
@endsection
