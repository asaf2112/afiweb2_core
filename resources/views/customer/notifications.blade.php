@extends('layouts.app')

@section('title', 'Bildirimlerim | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-afiGray py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-black font-heading tracking-tighter text-afiDark flex items-center gap-3 mb-8">
            <i class="fa-solid fa-bell text-yellow-500"></i> BİLDİRİMLERİM
        </h1>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sol Menü -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 sticky top-24">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-50 hover:text-afiDark transition-colors">
                                <i class="fa-solid fa-user"></i> Profilim
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-50 hover:text-afiDark transition-colors">
                                <i class="fa-solid fa-box"></i> Siparişlerim
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-50 hover:text-afiDark transition-colors">
                                <i class="fa-solid fa-heart"></i> Favorilerim
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold bg-yellow-500 text-afiDark shadow-lg shadow-yellow-500/20 transition-colors">
                                <i class="fa-solid fa-bell"></i> Bildirimlerim
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sağ İçerik -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="p-4 rounded-2xl border {{ !$notification->is_read ? 'border-yellow-300 bg-yellow-50/50' : 'border-gray-100 bg-white' }} flex gap-4 items-start transition-colors relative">
                                    <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xl shrink-0">
                                        <i class="fa-solid fa-tags"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="font-bold text-afiDark text-lg flex items-center gap-2">
                                                Fiyat Düşüşü!
                                                @if(!$notification->is_read)
                                                    <span class="bg-red-500 text-white text-[10px] uppercase font-black px-2 py-0.5 rounded-md">Yeni</span>
                                                @endif
                                            </h4>
                                            <span class="text-xs text-gray-400 font-medium whitespace-nowrap ml-4">{{ $notification->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-3">
                                            Alarm kurduğunuz <strong>{{ $notification->product->title ?? 'Bir ürün' }}</strong> ürününün fiyatı, hedeflediğiniz <strong>{{ number_format($notification->target_price, 2) }} ₺</strong> değerine veya altına düştü!
                                        </p>
                                        @if($notification->product)
                                            <a href="{{ route('products.show', $notification->product->slug) }}" class="inline-block bg-afiDark text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-500 hover:text-afiDark transition-colors shadow-sm">
                                                Hemen İncele
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-4xl text-gray-300 mb-4">
                                <i class="fa-solid fa-bell-slash"></i>
                            </div>
                            <h3 class="text-xl font-bold text-afiDark mb-2">Bildiriminiz Yok</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">Şu an için tetiklenmiş bir fiyat alarmınız veya başka bir bildiriminiz bulunmuyor.</p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-6 px-6 py-3 bg-yellow-500 text-afiDark font-bold rounded-xl hover:bg-yellow-400 transition-colors shadow-lg shadow-yellow-500/20">
                                Alışverişe Dön
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sayfa yüklendiğinde, okunmamış bildirimleri arka planda okundu olarak işaretle
    document.addEventListener('DOMContentLoaded', function() {
        const hasUnread = {{ $notifications->where('is_read', false)->count() > 0 ? 'true' : 'false' }};
        if (hasUnread) {
            fetch('{{ route('profile.notifications.read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            // Update header badge if exists
            setTimeout(() => {
                const headerBadge = document.getElementById('nav-notification-badge');
                if(headerBadge) {
                    headerBadge.classList.add('hidden');
                }
            }, 1000);
        }
    });
</script>
@endsection
