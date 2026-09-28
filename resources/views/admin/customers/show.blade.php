@extends('admin.layouts.app')

@section('title', 'Müşteri Detay | Admin Panel')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-black text-white mb-2">Müşteri Profili</h1>
        <p class="text-slate-400"><span class="text-adminYellow font-bold">{{ $customer->name }}</span> adlı müşterinin sipariş geçmişi ve detayları.</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold py-2 px-6 rounded-xl transition-colors border border-slate-700 flex items-center gap-2 shadow-lg">
        <i class="fa-solid fa-arrow-left"></i> Geri Dön
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profil Bilgileri -->
    <div class="lg:col-span-1">
        <div class="bg-adminCard rounded-2xl border border-adminBorder p-6 sticky top-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 rounded-full bg-slate-800 flex items-center justify-center text-adminYellow text-3xl font-black mx-auto mb-4 border border-slate-700 shadow-lg">
                    {{ strtoupper(mb_substr($customer->name, 0, 1, 'UTF-8')) }}
                </div>
                <h2 class="text-xl font-bold text-white">{{ $customer->name }}</h2>
                <p class="text-slate-400 text-sm mt-1">Müşteri (Kayıtlı Kullanıcı)</p>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-700 pb-3">
                    <span class="text-slate-400 text-sm">ID</span>
                    <span class="text-white font-semibold">#{{ $customer->id }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-700 pb-3">
                    <span class="text-slate-400 text-sm">E-Posta</span>
                    <span class="text-white font-medium">{{ $customer->email }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-700 pb-3">
                    <span class="text-slate-400 text-sm">Kayıt Tarihi</span>
                    <span class="text-white font-medium">{{ $customer->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-sm">Toplam Sipariş</span>
                    <span class="text-adminYellow font-bold">{{ $customer->orders ? $customer->orders->count() : 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sipariş Geçmişi -->
    <div class="lg:col-span-2">
        <div class="bg-adminCard rounded-2xl border border-adminBorder overflow-hidden">
            <div class="p-6 border-b border-adminBorder bg-slate-800/30">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-cart-arrow-down text-adminYellow"></i> Sipariş Geçmişi
                </h3>
            </div>
            
            <div class="p-0">
                @if($customer->orders && $customer->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-800/50 border-b border-adminBorder text-slate-400 text-xs uppercase tracking-wider">
                                    <th class="p-4 font-semibold">Sipariş No</th>
                                    <th class="p-4 font-semibold">Tarih</th>
                                    <th class="p-4 font-semibold">Tutar</th>
                                    <th class="p-4 font-semibold">Durum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-adminBorder">
                                @foreach($customer->orders as $order)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="p-4 text-white font-medium">#{{ $order->order_number ?? $order->id }}</td>
                                    <td class="p-4 text-slate-400 text-sm">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="p-4 text-white font-bold">{{ number_format($order->total_amount ?? 0, 2) }} ₺</td>
                                    <td class="p-4">
                                        @if(($order->status ?? '') == 'completed')
                                            <span class="bg-green-500/10 text-green-400 border border-green-500/20 px-2.5 py-1 rounded-full text-xs font-semibold">Tamamlandı</span>
                                        @elseif(($order->status ?? '') == 'cancelled')
                                            <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2.5 py-1 rounded-full text-xs font-semibold">İptal Edildi</span>
                                        @else
                                            <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2.5 py-1 rounded-full text-xs font-semibold">Bekliyor</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center text-slate-400">
                        <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="fa-solid fa-box-open opacity-50"></i>
                        </div>
                        <p>Kullanıcının henüz hiçbir siparişi bulunmuyor.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
