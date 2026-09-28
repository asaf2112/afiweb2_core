@extends('layouts.app')

@section('title', 'Siparişlerim | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-afiGray py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black font-heading tracking-tighter text-afiDark">SİPARİŞLERİM</h1>
                <p class="text-gray-500 mt-1">Geçmiş siparişlerinizi ve durumlarını buradan takip edebilirsiniz.</p>
            </div>
            <a href="{{ route('profile') }}" class="bg-white border border-gray-200 text-gray-700 font-bold py-2 px-6 rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Profile Dön
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @if(count($orders) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-5 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">Sipariş No</th>
                                <th class="py-5 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">Tarih</th>
                                <th class="py-5 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">Durum</th>
                                <th class="py-5 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider text-right">Toplam Tutar</th>
                                <th class="py-5 px-6 text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 font-bold text-afiDark">
                                        {{ $order->reference_code ?? 'Sipariş #' . $order->id }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 font-medium">
                                        {{ $order->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $statusColor = match($order->status) {
                                                'Bekliyor', 'Beklemede' => 'bg-yellow-100 text-yellow-700',
                                                'Onaylandı' => 'bg-blue-100 text-blue-700',
                                                'Kargoya Verildi', 'Kargolandı' => 'bg-purple-100 text-purple-700',
                                                'Tamamlandı' => 'bg-emerald-100 text-emerald-700',
                                                'İptal Edildi' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-gray-100 text-gray-600'
                                            };
                                        @endphp
                                        <span class="{{ $statusColor }} px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm border border-black/5">
                                            @if(in_array($order->status, ['Bekliyor', 'Beklemede'])) <i class="fa-regular fa-clock"></i>
                                            @elseif($order->status == 'Onaylandı') <i class="fa-solid fa-circle-check"></i>
                                            @elseif(in_array($order->status, ['Kargoya Verildi', 'Kargolandı'])) <i class="fa-solid fa-truck-fast"></i>
                                            @elseif($order->status == 'Tamamlandı') <i class="fa-solid fa-handshake"></i>
                                            @elseif($order->status == 'İptal Edildi') <i class="fa-solid fa-ban"></i>
                                            @endif
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-afiDark text-lg">
                                        {{ number_format($order->total_amount, 2) }} ₺
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-afiDark font-bold py-2 px-4 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition shadow-sm text-sm">
                                            <i class="fa-solid fa-eye text-yellow-500"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-16 text-center">
                    <div class="w-24 h-24 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-afiDark mb-2">Sipariş Bulunamadı</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Henüz hiçbir sipariş vermemişsiniz. Geniş ürün yelpazemizi inceleyerek hemen alışverişe başlayabilirsiniz.</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-afiDark text-white font-bold py-3 px-8 rounded-xl hover:bg-gray-800 transition shadow-lg">
                        Alışverişe Başla
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
