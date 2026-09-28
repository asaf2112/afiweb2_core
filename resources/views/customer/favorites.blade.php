@extends('layouts.app')

@section('title', 'Favorilerim | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-[#0b0f19] text-white py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Decorative Glows --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-800/80 pb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-10 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-500 flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <h1 class="text-3xl font-black font-heading tracking-tight text-white">FAVORİLERİM</h1>
                    <span id="fav-page-badge" class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs font-bold px-3 py-1 rounded-full">
                        {{ count($favorites) }} Ürün
                    </span>
                </div>
                <p class="text-gray-400 text-sm">Beğendiğiniz ürünleri buradan takip edebilir ve tek tıkla sepetinize ekleyebilirsiniz.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.index') }}" class="bg-gray-800/80 hover:bg-gray-700 text-gray-200 font-bold py-2.5 px-5 rounded-xl border border-gray-700 transition flex items-center gap-2 text-xs">
                    <i class="fa-solid fa-store text-yellow-500"></i> Alışverişe Devam Et
                </a>
            </div>
        </div>

        @if(count($favorites) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($favorites as $product)
                <div class="bg-[#12151c] rounded-2xl p-4 border border-gray-800 hover:border-yellow-500/50 transition-all duration-300 group flex flex-col h-full relative shadow-xl hover:shadow-yellow-500/10" id="favorite-card-{{ $product->id }}">
                    
                    {{-- Remove Favorite Button --}}
                    <button type="button" 
                            onclick="removeFavoritePage({{ $product->id }})" 
                            class="absolute top-4 right-4 z-10 w-9 h-9 bg-gray-900/90 border border-red-500/30 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all duration-200 shadow-md"
                            title="Favorilerden Çıkar">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>

                    {{-- Product Image --}}
                    <div class="relative mb-4 bg-[#181c24] rounded-xl p-4 aspect-square overflow-hidden flex items-center justify-center border border-gray-800/60">
                        @php
                            $imageUrl = 'https://placehold.co/400x400/1e293b/94a3b8?text=Gorsel+Yok';
                            if ($product->main_image) {
                                if (file_exists(public_path($product->main_image))) {
                                    $imageUrl = asset($product->main_image);
                                } elseif (file_exists(public_path('storage/' . $product->main_image))) {
                                    $imageUrl = asset('storage/' . $product->main_image);
                                }
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $product->title }}" class="object-contain w-full h-full max-h-[160px] group-hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    {{-- Details --}}
                    <div class="flex flex-col flex-1">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-bold text-white mb-2 leading-snug hover:text-yellow-400 transition-colors line-clamp-2">
                            {{ $product->title }}
                        </a>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $product->condition_type == 'used' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-blue-500/10 text-blue-400 border border-blue-500/30' }}">
                                {{ $product->condition_type == 'used' ? 'İkinci El' : 'Sıfır Ürün' }}
                            </span>
                            @if($product->stock > 0)
                                <span class="text-[11px] font-bold text-emerald-400"><i class="fa-solid fa-check text-[10px]"></i> Stokta Var</span>
                            @else
                                <span class="text-[11px] font-bold text-gray-500"><i class="fa-solid fa-xmark text-[10px]"></i> Stokta Yok</span>
                            @endif
                        </div>
                        
                        {{-- Price and Actions --}}
                        <div class="mt-auto pt-3 border-t border-gray-800/80">
                            <div class="mb-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Fiyat</p>
                                <p class="text-xl font-black text-yellow-400">{{ number_format($product->price, 2, ',', '.') }} ₺</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" 
                                        onclick="addToCartQuick(this, {{ $product->id }})" 
                                        class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold py-2.5 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1.5 shadow-md shadow-yellow-500/20">
                                    <i class="fa-solid fa-cart-shopping text-xs"></i> Sepete Ekle
                                </button>
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2.5 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1 border border-gray-700">
                                    İncele <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#12151c] rounded-3xl border border-gray-800 p-16 text-center shadow-2xl max-w-2xl mx-auto my-12">
                <div class="w-20 h-20 bg-red-500/10 text-red-400 border border-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 font-heading">Favorileriniz Henüz Boş</h3>
                <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto leading-relaxed">
                    Beğendiğiniz ürünleri ürün kartlarındaki kalp ikonuna tıklayarak favorilerinize ekleyebilir ve dilediğiniz an tek tıkla sipariş verebilirsiniz.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-btn text-afiDark font-black py-3.5 px-8 rounded-xl transition shadow-lg shadow-yellow-500/20">
                    <i class="fa-solid fa-fire text-sm"></i> Ürünleri Keşfe Başla
                </a>
            </div>
        @endif
    </div>
</div>

<script>
async function removeFavoritePage(productId) {
    const card = document.getElementById('favorite-card-' + productId);
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
        
        // Update header badges
        const headerBadge = document.getElementById('favorite-badge-count');
        if(headerBadge && data.favCount !== undefined) {
            headerBadge.innerText = data.favCount;
            if(data.favCount > 0) {
                headerBadge.classList.remove('hidden');
            } else {
                headerBadge.classList.add('hidden');
            }
        }

        const pageBadge = document.getElementById('fav-page-badge');
        if(pageBadge) {
            pageBadge.innerText = `${data.favCount} Ürün`;
        }

        if (card) {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                if (document.querySelectorAll('[id^="favorite-card-"]').length === 0) {
                    window.location.reload();
                }
            }, 300);
        }
    } catch (error) {
        console.error("Favori silme hatası:", error);
    }
}
</script>
@endsection
