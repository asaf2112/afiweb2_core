@extends('layouts.app')

@section('title', 'Sepetim | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-gray-950 text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-black font-heading tracking-tighter text-white mb-8 flex items-center gap-3">
            <i class="fa-solid fa-cart-shopping text-yellow-500"></i> SEPETİM
        </h1>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3.5 rounded-2xl mb-6 flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3.5 rounded-2xl mb-6 flex items-center gap-2 text-sm font-bold">
                <i class="fa-solid fa-triangle-exclamation text-red-400 text-base"></i> {{ session('error') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sepet Listesi -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-gray-900 rounded-3xl shadow-2xl border border-gray-800 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-950/80 border-b border-gray-800">
                                    <tr>
                                        <th class="py-4 px-6 font-black text-gray-400 text-xs uppercase tracking-wider">Ürün</th>
                                        <th class="py-4 px-6 font-black text-gray-400 text-xs uppercase tracking-wider">Fiyat</th>
                                        <th class="py-4 px-6 font-black text-gray-400 text-xs uppercase tracking-wider text-center">Adet</th>
                                        <th class="py-4 px-6 font-black text-gray-400 text-xs uppercase tracking-wider text-right">Toplam</th>
                                        <th class="py-4 px-6"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/80">
                                    @foreach($cart as $id => $details)
                                        <tr class="hover:bg-gray-800/40 transition-colors">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-16 h-16 bg-gray-950 rounded-2xl border border-gray-800 overflow-hidden flex items-center justify-center shrink-0">
                                                        @php
                                                            $imageUrl = 'https://via.placeholder.com/100?text=Gorsel+Yok';
                                                            if (!empty($details['image'])) {
                                                                if (file_exists(public_path($details['image']))) {
                                                                    $imageUrl = asset($details['image']);
                                                                } elseif (file_exists(public_path('storage/' . $details['image']))) {
                                                                    $imageUrl = asset('storage/' . $details['image']);
                                                                }
                                                            }
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" alt="{{ $details['name'] }}" class="object-cover w-full h-full p-1 rounded-xl">
                                                    </div>
                                                    <a href="{{ !empty($details['slug']) ? route('products.show', $details['slug']) : '#' }}" class="font-bold text-white hover:text-yellow-400 transition-colors line-clamp-2 text-sm leading-snug">
                                                        {{ $details['name'] }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-gray-300 font-bold text-sm whitespace-nowrap">
                                                {{ number_format($details['price'], 2, ',', '.') }} TL
                                            </td>
                                            <td class="py-4 px-6">
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 text-center py-1.5 px-2 bg-gray-950 border border-gray-800 text-yellow-400 font-bold text-sm rounded-xl focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                                    <button type="submit" class="w-8 h-8 bg-gray-800 hover:bg-yellow-500 hover:text-afiDark text-gray-300 rounded-xl flex items-center justify-center transition cursor-pointer" title="Güncelle">
                                                        <i class="fa-solid fa-rotate-right text-xs"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="py-4 px-6 text-right font-black text-yellow-400 text-base whitespace-nowrap">
                                                {{ number_format($details['price'] * $details['quantity'], 2, ',', '.') }} TL
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-8 h-8 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white border border-red-500/20 rounded-xl flex items-center justify-center transition cursor-pointer" title="Kaldır">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sepet Özeti -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-gray-900 rounded-3xl shadow-2xl border border-gray-800 p-8 sticky top-28 space-y-6">
                        <h2 class="text-xl font-black text-white pb-4 border-b border-gray-800 flex items-center justify-between">
                            <span>Sipariş Özeti</span>
                            <i class="fa-solid fa-receipt text-yellow-500"></i>
                        </h2>
                        
                        <div class="space-y-4 text-xs font-bold">
                            <div class="flex justify-between items-center text-gray-400">
                                <span>Ara Toplam</span>
                                <span class="text-gray-200 font-black">{{ number_format($total, 2, ',', '.') }} TL</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-400">
                                <span>KDV</span>
                                <span class="text-emerald-400 font-black">Dahil</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-400">
                                <span>Kargo</span>
                                <span class="text-emerald-400 font-black">Ücretsiz</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-800 flex justify-between items-end">
                            <span class="text-xs text-gray-400 font-bold uppercase">Genel Toplam</span>
                            <span class="text-3xl font-black text-yellow-400">{{ number_format($total, 2, ',', '.') }} TL</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="block w-full bg-yellow-500 hover:bg-yellow-400 text-afiDark text-center font-black py-4 px-6 rounded-2xl transition shadow-lg shadow-yellow-500/20 flex items-center justify-center gap-2 cursor-pointer text-sm">
                            Satın Al <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        
                        <a href="{{ route('products.index') }}" class="block w-full text-center font-bold text-gray-400 hover:text-white text-xs transition">
                            Alışverişe Dön
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-900 rounded-3xl shadow-2xl border border-gray-800 p-16 text-center">
                <div class="w-24 h-24 bg-gray-950 text-yellow-500/40 border border-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-2">Sepetiniz Boş</h3>
                <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto">Sepetinizde henüz bir ürün bulunmuyor. Alışverişe başlamak için ürünlerimizi inceleyebilirsiniz.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-yellow-500 text-afiDark font-black py-3.5 px-8 rounded-2xl hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20 text-sm">
                    Alışverişe Başla
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
