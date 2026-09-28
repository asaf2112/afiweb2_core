@extends('layouts.app')

@section('title', 'Sipariş Detayı | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-afiGray py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black font-heading tracking-tighter text-afiDark flex items-center gap-3">
                    <i class="fa-solid fa-file-invoice text-yellow-500"></i> SİPARİŞ DETAYI
                </h1>
                <p class="text-gray-500 mt-1 font-bold font-mono">{{ $order->reference_code ?? 'Sipariş #' . $order->id }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="bg-white border border-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-2xl hover:bg-gray-50 transition flex items-center gap-2 shadow-sm text-sm">
                <i class="fa-solid fa-arrow-left"></i> tüm Siparişlerim
            </a>
        </div>

        <!-- Order Summary Top Bar -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-wrap gap-8 justify-between items-center relative overflow-hidden">
            <div class="absolute -right-10 -top-10 text-9xl text-gray-50 opacity-50 pointer-events-none">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sipariş Tarihi</p>
                <p class="text-lg font-bold text-afiDark">{{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sipariş Durumu</p>
                @php
                    $statusColor = match($order->status) {
                        'Bekliyor', 'Beklemede' => 'text-yellow-600',
                        'Onaylandı' => 'text-blue-600',
                        'Kargoya Verildi', 'Kargolandı' => 'text-purple-600',
                        'Tamamlandı' => 'text-emerald-600',
                        'İptal Edildi' => 'text-rose-600',
                        default => 'text-gray-600'
                    };
                @endphp
                <p class="text-lg font-black {{ $statusColor }}">{{ $order->status }}</p>
            </div>
            <div class="relative z-10 text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Toplam Tutar</p>
                <p class="text-2xl font-black text-afiDark">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</p>
            </div>
        </div>

        <!-- 🚚 KARGO TAKİP KARTI (Varsa Öne Çıkarılır) -->
        @if($order->tracking_number)
            <div class="bg-gradient-to-r from-purple-900 to-indigo-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none flex items-center pr-6 text-9xl">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 bg-purple-500/30 text-purple-200 border border-purple-400/30 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-truck-fast text-yellow-400"></i> Kargo Takip Bilgisi
                        </div>
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            {{ $order->shipping_company ?? 'Kargo Firması' }}
                        </h3>
                        <p class="text-purple-200 text-xs">
                            Takip Numarası: <span class="font-mono font-bold text-white text-sm tracking-wider select-all">{{ $order->tracking_number }}</span>
                        </p>
                        @if($order->shipped_at)
                            <p class="text-[11px] text-purple-300">Kargolanma Tarihi: {{ $order->shipped_at->format('d.m.Y — H:i') }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ $order->tracking_url }}" target="_blank" 
                           class="bg-yellow-400 hover:bg-yellow-300 text-afiDark font-black px-6 py-3 rounded-2xl transition shadow-lg flex items-center gap-2 text-sm transform hover:scale-105">
                            <i class="fa-solid fa-location-dot"></i> Kargomu Takip Et
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sipariş İlerleme Durumu (Progress Stepper) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <h3 class="font-bold text-afiDark mb-8 flex items-center gap-2 text-lg">
                <i class="fa-solid fa-bars-progress text-yellow-500"></i> Sipariş Süreci
            </h3>
            
            @php
                $steps = ['Bekliyor', 'Onaylandı', 'Kargoya Verildi', 'Tamamlandı'];
                $currentStatus = $order->status;
                if ($currentStatus == 'Beklemede') $currentStatus = 'Bekliyor';
                if ($currentStatus == 'Kargolandı') $currentStatus = 'Kargoya Verildi';

                $isCanceled = ($currentStatus == 'İptal Edildi');
                $currentStepIndex = array_search($currentStatus, $steps);
                if ($currentStepIndex === false) $currentStepIndex = 0;
            @endphp

            @if($isCanceled)
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-4xl mb-2"></i>
                    <h4 class="font-bold text-rose-800 text-lg">Sipariş İptal Edildi</h4>
                    <p class="text-xs text-rose-600 mt-1">Bu sipariş talebiniz veya yönetim kararıyla iptal edilmiştir. Sorularınız için bizimle iletişime geçebilirsiniz.</p>
                </div>
            @else
                <div class="relative">
                    <!-- Desktop Horizontal Line -->
                    <div class="absolute left-0 top-6 transform -translate-y-1/2 w-full h-1.5 bg-gray-100 rounded-full hidden md:block"></div>
                    <div class="absolute left-0 top-6 transform -translate-y-1/2 h-1.5 bg-gradient-to-r from-yellow-400 via-purple-500 to-emerald-500 rounded-full hidden md:block transition-all duration-1000 ease-in-out" style="width: {{ ($currentStepIndex / (count($steps) - 1)) * 100 }}%"></div>

                    <div class="flex flex-col md:flex-row justify-between relative z-10 gap-8 md:gap-0">
                        @foreach($steps as $index => $step)
                            @php
                                $isCompleted = $index < $currentStepIndex;
                                $isActive = $index === $currentStepIndex;
                                $isPending = $index > $currentStepIndex;
                                
                                $icon = 'fa-clock';
                                if($step == 'Onaylandı') $icon = 'fa-check-double';
                                if($step == 'Kargoya Verildi') $icon = 'fa-truck-fast';
                                if($step == 'Tamamlandı') $icon = 'fa-handshake';
                                
                                $iconColor = $isActive ? 'text-yellow-500' : ($isCompleted ? 'text-emerald-500' : 'text-gray-300');
                                $borderColor = $isActive ? 'border-yellow-500 shadow-lg shadow-yellow-500/30 bg-yellow-50' : ($isCompleted ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 bg-white');
                            @endphp
                            
                            <div class="flex md:flex-col items-center md:justify-center gap-4 md:gap-3 text-left md:text-center w-full md:w-1/4 relative group">
                                
                                <!-- Mobile Vertical Line -->
                                @if(!$loop->last)
                                    <div class="absolute left-[23px] top-12 bottom-[-2rem] w-1 {{ $isCompleted ? 'bg-emerald-500' : 'bg-gray-100' }} md:hidden z-0 rounded-full"></div>
                                @endif

                                <div class="w-12 h-12 shrink-0 rounded-full flex items-center justify-center text-xl {{ $iconColor }} {{ $borderColor }} border-4 transition-all duration-500 transform relative z-10 {{ $isActive ? 'scale-110' : '' }}">
                                    @if($isCompleted)
                                        <i class="fa-solid fa-check text-base"></i>
                                    @else
                                        <i class="fa-solid {{ $icon }}"></i>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="font-bold text-base {{ $isActive ? 'text-afiDark' : ($isCompleted ? 'text-gray-800' : 'text-gray-400') }} transition-colors">{{ $step }}</p>
                                    @if($isActive)
                                        <p class="text-xs font-bold text-yellow-600 mt-0.5 animate-pulse">Şu anki aşama</p>
                                    @elseif($isCompleted)
                                        <p class="text-xs font-semibold text-emerald-600 mt-0.5"><i class="fa-solid fa-check-double"></i> Tamamlandı</p>
                                    @else
                                        <p class="text-xs font-medium text-gray-400 mt-0.5">Bekliyor</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sipariş İçeriği -->
        <h2 class="text-xl font-bold text-afiDark flex items-center gap-2">
            <i class="fa-solid fa-box-open text-yellow-500"></i> Sipariş İçeriği
        </h2>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider">Ürün</th>
                        <th class="py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider text-center">Birim Fiyat</th>
                        <th class="py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider text-center">Adet</th>
                        <th class="py-4 px-6 font-bold text-gray-700 text-sm uppercase tracking-wider text-right">Ara Toplam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0 border border-gray-200 p-1">
                                        @php
                                            $imgUrl = asset('images/og-default.jpg');
                                            if ($item->product && $item->product->main_image) {
                                                $imgUrl = \Illuminate\Support\Str::startsWith($item->product->main_image, ['http'])
                                                    ? $item->product->main_image
                                                    : asset($item->product->main_image);
                                            }
                                        @endphp
                                        <img src="{{ $imgUrl }}" alt="{{ $item->product_name }}" class="object-contain w-full h-full">
                                    </div>
                                    <div>
                                        <p class="font-bold text-afiDark">{{ $item->product_name }}</p>
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-xs font-bold text-yellow-600 hover:text-yellow-700 flex items-center gap-1 mt-1">Ürüne Git <i class="fa-solid fa-angle-right"></i></a>
                                        @else
                                            <p class="text-xs text-rose-500 font-semibold mt-1">Bu ürün artık satışta değil</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-600 font-medium text-center">
                                {{ number_format($item->price, 2, ',', '.') }} ₺
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-afiDark">
                                x{{ $item->quantity }}
                            </td>
                            <td class="py-4 px-6 text-right font-black text-afiDark">
                                {{ number_format($item->price * $item->quantity, 2, ',', '.') }} ₺
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
