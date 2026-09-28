@extends('admin.layouts.app')

@section('title', 'Sipariş Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-boxes-packing text-adminAccent"></i> Sipariş Yönetimi
            </h1>
            <p class="text-gray-500 text-sm mt-1">Müşteri siparişlerini anlık olarak takip edebilir, durum güncelleyebilir ve kargo takibini yönetebilirsiniz.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-rotate"></i> Yenile
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- Tümü -->
        <a href="{{ route('admin.orders.index') }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ !request('status') ? 'border-amber-400 bg-amber-50/20 ring-1 ring-amber-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-gray-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">TÜMÜ</span>
                <i class="fa-solid fa-list-check text-amber-500"></i>
            </div>
            <p class="text-2xl font-black text-gray-800">{{ $stats['total'] }}</p>
        </a>
        <!-- Bekliyor -->
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'Bekliyor'])) }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ request('status') == 'Bekliyor' ? 'border-yellow-400 bg-yellow-50/30 ring-1 ring-yellow-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-yellow-600 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">BEKLİYOR</span>
                <i class="fa-regular fa-clock"></i>
            </div>
            <p class="text-2xl font-black text-yellow-600">{{ $stats['bekliyor'] }}</p>
        </a>
        <!-- Onaylandı -->
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'Onaylandı'])) }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ request('status') == 'Onaylandı' ? 'border-blue-400 bg-blue-50/30 ring-1 ring-blue-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-blue-600 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">ONAYLANDI</span>
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="text-2xl font-black text-blue-600">{{ $stats['onaylandi'] }}</p>
        </a>
        <!-- Kargoda -->
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'Kargoya Verildi'])) }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ request('status') == 'Kargoya Verildi' ? 'border-purple-400 bg-purple-50/30 ring-1 ring-purple-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-purple-600 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">KARGODA</span>
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <p class="text-2xl font-black text-purple-600">{{ $stats['kargoda'] }}</p>
        </a>
        <!-- Tamamlandı -->
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'Tamamlandı'])) }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ request('status') == 'Tamamlandı' ? 'border-emerald-400 bg-emerald-50/30 ring-1 ring-emerald-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-emerald-600 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">TAMAMLANDI</span>
                <i class="fa-solid fa-handshake"></i>
            </div>
            <p class="text-2xl font-black text-emerald-600">{{ $stats['tamamlandi'] }}</p>
        </a>
        <!-- İptal Edildi -->
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['status' => 'İptal Edildi'])) }}" 
           class="bg-white p-4 rounded-2xl border transition-all shadow-sm hover:shadow-md {{ request('status') == 'İptal Edildi' ? 'border-rose-400 bg-rose-50/30 ring-1 ring-rose-400' : 'border-gray-100' }}">
            <div class="flex justify-between items-center text-rose-600 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">İPTAL</span>
                <i class="fa-solid fa-ban"></i>
            </div>
            <p class="text-2xl font-black text-rose-600">{{ $stats['iptal'] }}</p>
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 space-y-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Keyword Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Arama</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Sipariş kodu, müşteri adı, e-posta veya kargo no..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-semibold text-gray-700 focus:outline-none focus:border-adminAccent focus:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Sipariş Durumu</label>
                <select name="status" class="w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-3 py-2.5 text-xs font-semibold focus:outline-none focus:border-adminAccent focus:bg-white transition-all">
                    <option value="">Tüm Durumlar</option>
                    <option value="Bekliyor" {{ request('status') == 'Bekliyor' ? 'selected' : '' }}>⏳ Bekliyor</option>
                    <option value="Onaylandı" {{ request('status') == 'Onaylandı' ? 'selected' : '' }}>✅ Onaylandı</option>
                    <option value="Kargoya Verildi" {{ request('status') == 'Kargoya Verildi' ? 'selected' : '' }}>🚚 Kargoya Verildi</option>
                    <option value="Tamamlandı" {{ request('status') == 'Tamamlandı' ? 'selected' : '' }}>🎉 Tamamlandı</option>
                    <option value="İptal Edildi" {{ request('status') == 'İptal Edildi' ? 'selected' : '' }}>❌ İptal Edildi</option>
                </select>
            </div>

            <!-- Date Preset -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Tarih Filtresi</label>
                <select name="date_preset" class="w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-3 py-2.5 text-xs font-semibold focus:outline-none focus:border-adminAccent focus:bg-white transition-all">
                    <option value="">Tüm Zamanlar</option>
                    <option value="today" {{ request('date_preset') == 'today' ? 'selected' : '' }}>Bugün</option>
                    <option value="this_week" {{ request('date_preset') == 'this_week' ? 'selected' : '' }}>Bu Hafta</option>
                    <option value="this_month" {{ request('date_preset') == 'this_month' ? 'selected' : '' }}>Bu Ay</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-afiDark hover:bg-gray-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i> Filtrele
                </button>
                <a href="{{ route('admin.orders.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs" title="Filtreleri Sıfırla">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Sipariş No & Tarih</th>
                        <th class="py-4 px-6">Müşteri</th>
                        <th class="py-4 px-6 text-center">İçerik & Tutar</th>
                        <th class="py-4 px-6 text-center">Durum (Anlık Değiştir)</th>
                        <th class="py-4 px-6">Kargo Takip</th>
                        <th class="py-4 px-6 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($orders as $order)
                    @php
                        $statusClass = match($order->status) {
                            'Bekliyor', 'Beklemede' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'Onaylandı' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Kargoya Verildi', 'Kargolandı' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Tamamlandı' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'İptal Edildi' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition">
                        <!-- Order Code & Date -->
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-900 font-mono flex items-center gap-1.5">
                                <i class="fa-solid fa-receipt text-xs text-adminAccent"></i>
                                {{ $order->reference_code ?? 'ORD-' . $order->id }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="fa-regular fa-calendar text-[10px]"></i>
                                {{ $order->created_at->format('d.m.Y — H:i') }}
                            </div>
                        </td>

                        <!-- Customer Info -->
                        <td class="py-4 px-6">
                            @if($order->user)
                                <div class="font-bold text-gray-800 leading-tight">{{ $order->user->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $order->user->email ?? $order->user->phone }}</div>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-xs italic">Misafir Müşteri</span>
                            @endif
                        </td>

                        <!-- Total & Items -->
                        <td class="py-4 px-6 text-center">
                            <div class="font-black text-gray-900 text-base">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</div>
                            <div class="text-[11px] font-bold text-gray-400 mt-0.5">
                                {{ $order->items->sum('quantity') }} Parça Ürün
                            </div>
                        </td>

                        <!-- Status Inline Switcher -->
                        <td class="py-4 px-6 text-center">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline-block" id="status-form-{{ $order->id }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <select name="status" 
                                            onchange="handleQuickStatusChange(this, {{ $order->id }})"
                                            class="px-3 py-1.5 rounded-full text-xs font-bold border cursor-pointer focus:outline-none transition shadow-sm {{ $statusClass }}">
                                        <option value="Bekliyor" {{ in_array($order->status, ['Bekliyor', 'Beklemede']) ? 'selected' : '' }}>⏳ Bekliyor</option>
                                        <option value="Onaylandı" {{ $order->status == 'Onaylandı' ? 'selected' : '' }}>✅ Onaylandı</option>
                                        <option value="Kargoya Verildi" {{ in_array($order->status, ['Kargoya Verildi', 'Kargolandı']) ? 'selected' : '' }}>🚚 Kargoya Verildi</option>
                                        <option value="Tamamlandı" {{ $order->status == 'Tamamlandı' ? 'selected' : '' }}>🎉 Tamamlandı</option>
                                        <option value="İptal Edildi" {{ $order->status == 'İptal Edildi' ? 'selected' : '' }}>❌ İptal Edildi</option>
                                    </select>
                                </form>
                            </div>
                        </td>

                        <!-- Tracking Info -->
                        <td class="py-4 px-6">
                            @if($order->tracking_number)
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1">
                                        <i class="fa-solid fa-truck text-purple-500 text-[10px]"></i>
                                        {{ $order->shipping_company ?? 'Kargo' }}
                                    </span>
                                    <a href="{{ $order->tracking_url }}" target="_blank" 
                                       class="text-xs font-mono font-bold text-purple-600 hover:text-purple-800 hover:underline flex items-center gap-1 mt-0.5" 
                                       title="Kargo Takip Sayfası">
                                        {{ $order->tracking_number }}
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>
                                </div>
                            @elseif(in_array($order->status, ['Kargoya Verildi', 'Kargolandı']))
                                <button type="button" 
                                        onclick="openCargoModal({{ $order->id }}, '{{ $order->reference_code ?? 'ORD-'.$order->id }}', '{{ $order->shipping_company }}', '{{ $order->tracking_number }}')"
                                        class="px-2.5 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold rounded-lg transition flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Takip Kodu Ekle
                                </button>
                            @else
                                <span class="text-xs text-gray-400 italic">—</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                        onclick="openCargoModal({{ $order->id }}, '{{ $order->reference_code ?? 'ORD-'.$order->id }}', '{{ $order->shipping_company }}', '{{ $order->tracking_number }}')"
                                        class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 hover:bg-purple-100 transition flex items-center justify-center shadow-sm"
                                        title="Kargo Bilgisi Güncelle">
                                    <i class="fa-solid fa-truck"></i>
                                </button>
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100 transition flex items-center justify-center shadow-sm" 
                                   title="Detaylı Sipariş Ekranı">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-gray-400">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl text-gray-300">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <p class="font-bold text-gray-600 text-base">Sipariş Bulunamadı</p>
                            <p class="text-xs text-gray-400 mt-1">Seçtiğiniz filtrelere uygun hiç sipariş kaydı mevcut değil.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ============================================================
     KARGO GÜNCELLEME MODALI
============================================================ -->
<div id="cargo-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-gray-100 transform scale-95 transition-transform duration-300" id="cargo-modal-box">
        <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-5">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-purple-600"></i> Kargo Bilgisi Gir
            </h3>
            <button onclick="closeCargoModal()" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="cargo-modal-form" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="Kargoya Verildi">

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Sipariş Kodu</label>
                    <input type="text" id="modal-order-code" readonly class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-mono font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Kargo Firması</label>
                    <select name="shipping_company" id="modal-shipping-company" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:border-purple-500">
                        <option value="Yurtiçi Kargo">Yurtiçi Kargo</option>
                        <option value="Aras Kargo">Aras Kargo</option>
                        <option value="MNG Kargo">MNG Kargo</option>
                        <option value="Sürat Kargo">Sürat Kargo</option>
                        <option value="PTT Kargo">PTT Kargo</option>
                        <option value="Trendyol Express">Trendyol Express</option>
                        <option value="Hepsijet">Hepsijet</option>
                        <option value="Kolay Gelsin">Kolay Gelsin</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Kargo Takip Numarası</label>
                    <input type="text" name="tracking_number" id="modal-tracking-number" required placeholder="Örn: 123456789012" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-mono font-bold text-gray-800 focus:outline-none focus:border-purple-500">
                </div>

                <div class="p-3 bg-purple-50 border border-purple-100 rounded-xl text-xs text-purple-700 flex items-start gap-2">
                    <i class="fa-solid fa-bell text-purple-500 mt-0.5 shrink-0"></i>
                    <span>Kaydedildiğinde müşteriye kargo takip numarası ve link içeren SMS gönderilir.</span>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeCargoModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl text-xs hover:bg-gray-200 transition">
                    İptal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Kaydet ve SMS Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function handleQuickStatusChange(selectEl, orderId) {
    const newStatus = selectEl.value;
    
    // Eğer "Kargoya Verildi" seçildiyse ve henüz kargo no yoksa kargo modalını açalım
    if (newStatus === 'Kargoya Verildi') {
        const orderCode = selectEl.closest('tr').querySelector('.font-mono').innerText.trim();
        openCargoModal(orderId, orderCode, 'Yurtiçi Kargo', '');
        return;
    }

    // Normal form gönderimi
    document.getElementById('status-form-' + orderId).submit();
}

function openCargoModal(orderId, orderCode, company, tracking) {
    const modal = document.getElementById('cargo-modal');
    const modalBox = document.getElementById('cargo-modal-box');
    const form = document.getElementById('cargo-modal-form');
    
    form.action = '/admin/orders/' + orderId + '/status';
    document.getElementById('modal-order-code').value = orderCode;
    
    if (company) {
        document.getElementById('modal-shipping-company').value = company;
    }
    if (tracking) {
        document.getElementById('modal-tracking-number').value = tracking;
    } else {
        document.getElementById('modal-tracking-number').value = '';
    }

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalBox.classList.remove('scale-95');
    }, 10);
}

function closeCargoModal() {
    const modal = document.getElementById('cargo-modal');
    const modalBox = document.getElementById('cargo-modal-box');
    
    modal.classList.add('opacity-0');
    modalBox.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endsection
