@extends('admin.layouts.app')

@section('title', 'Sipariş Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-800">Sipariş Detayı</h1>
                <span class="font-mono font-bold text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-lg border border-gray-200">
                    {{ $order->reference_code ?? 'ORD-' . $order->id }}
                </span>
            </div>
            <p class="text-gray-500 text-xs mt-1">Sipariş Tarihi: <span class="font-bold text-gray-700">{{ $order->created_at->format('d M Y, H:i') }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-50 transition font-bold flex items-center gap-2 text-xs shadow-sm self-start">
            <i class="fa-solid fa-arrow-left"></i> Sipariş Listesine Dön
        </a>
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

    <!-- Status Stepper Bar -->
    @php
        $statusSteps = ['Bekliyor', 'Onaylandı', 'Kargoya Verildi', 'Tamamlandı'];
        $currentStatus = $order->status;
        if ($currentStatus == 'Beklemede') $currentStatus = 'Bekliyor';
        if ($currentStatus == 'Kargolandı') $currentStatus = 'Kargoya Verildi';

        $isCanceled = ($currentStatus == 'İptal Edildi');
        $stepIndex = array_search($currentStatus, $statusSteps);
        if ($stepIndex === false) $stepIndex = 0;
    @endphp

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
            <i class="fa-solid fa-bars-progress text-adminAccent"></i> Sipariş İlerleme Süreci
        </h3>

        @if($isCanceled)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-center">
                <i class="fa-solid fa-ban text-rose-500 text-2xl mb-2"></i>
                <p class="font-bold text-rose-700 text-sm">Bu Sipariş İptal Edilmiştir</p>
                <p class="text-xs text-rose-600 mt-1">Sipariş süreci durdurulmuştur.</p>
            </div>
        @else
            <div class="relative">
                <!-- Desktop Progress Line -->
                <div class="absolute left-0 top-5 transform -translate-y-1/2 w-full h-1 bg-gray-100 rounded-full hidden md:block"></div>
                <div class="absolute left-0 top-5 transform -translate-y-1/2 h-1 bg-gradient-to-r from-amber-400 via-purple-500 to-emerald-500 rounded-full hidden md:block transition-all duration-700"
                     style="width: {{ ($stepIndex / (count($statusSteps) - 1)) * 100 }}%"></div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                    @foreach($statusSteps as $idx => $stName)
                        @php
                            $done = $idx <= $stepIndex;
                            $active = $idx === $stepIndex;
                        @endphp
                        <div class="flex items-center md:flex-col md:text-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all shrink-0
                                {{ $active ? 'bg-amber-500 text-white border-amber-500 ring-4 ring-amber-100 scale-110 shadow-md' : ($done ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-400 border-gray-200') }}">
                                @if($done && !$active)
                                    <i class="fa-solid fa-check text-xs"></i>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold {{ $active ? 'text-gray-900' : ($done ? 'text-gray-700' : 'text-gray-400') }}">{{ $stName }}</p>
                                @if($active)
                                    <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full inline-block mt-0.5">Aktif Aşama</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Main Grid: Info + Status & Cargo Management -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- 1. Müşteri Bilgileri -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-user text-adminAccent"></i> Müşteri Bilgileri
            </h2>
            @if($order->user)
                <div class="space-y-3.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase">Ad Soyad</span>
                        <span class="font-bold text-gray-800 text-sm">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase">E-posta</span>
                        <span class="font-semibold text-gray-700">{{ $order->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase">Telefon</span>
                        <span class="font-semibold text-gray-800 font-mono">{{ $order->user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase">Kayıt Tarihi</span>
                        <span class="font-semibold text-gray-700">{{ $order->user->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <i class="fa-solid fa-user-slash text-3xl mb-2 text-gray-300"></i>
                    <p class="font-bold text-gray-500">Misafir Müşteri</p>
                </div>
            @endif
        </div>

        <!-- 2. Sipariş Özeti -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-file-invoice text-adminAccent"></i> Sipariş Ödeme Özeti
            </h2>
            <div class="space-y-3.5 text-xs">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase">Referans Kodu</span>
                    <span class="font-bold font-mono text-gray-800">{{ $order->reference_code ?? 'ORD-'.$order->id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase">Ödeme Türü</span>
                    <span class="font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                        <i class="fa-solid fa-credit-card text-[10px]"></i> Kredi Kartı / Online
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase">Ürün Adedi</span>
                    <span class="font-bold text-gray-800">{{ $order->items->sum('quantity') }} Adet</span>
                </div>
                <div class="pt-2 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-gray-500 font-bold text-xs uppercase">Toplam Tutar</span>
                    <span class="font-black text-xl text-gray-900">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</span>
                </div>
            </div>
        </div>

        <!-- 3. Durum & Kargo Entegrasyonu Yönetimi -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="font-bold text-gray-800 pb-3 border-b border-gray-100 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-truck-fast text-purple-600"></i> Durum & Kargo Yönetimi
            </h2>
            
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Durum Seçimi -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Sipariş Durumu</label>
                    <select name="status" id="detail-status-select" onchange="toggleCargoInputs(this.value)" class="w-full bg-gray-50 border border-gray-200 text-gray-800 font-bold rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-adminAccent">
                        <option value="Bekliyor" {{ in_array($order->status, ['Bekliyor', 'Beklemede']) ? 'selected' : '' }}>⏳ Bekliyor</option>
                        <option value="Onaylandı" {{ $order->status == 'Onaylandı' ? 'selected' : '' }}>✅ Onaylandı</option>
                        <option value="Kargoya Verildi" {{ in_array($order->status, ['Kargoya Verildi', 'Kargolandı']) ? 'selected' : '' }}>🚚 Kargoya Verildi</option>
                        <option value="Tamamlandı" {{ $order->status == 'Tamamlandı' ? 'selected' : '' }}>🎉 Tamamlandı</option>
                        <option value="İptal Edildi" {{ $order->status == 'İptal Edildi' ? 'selected' : '' }}>❌ İptal Edildi</option>
                    </select>
                </div>

                <!-- Kargo Alanları -->
                <div id="cargo-fields-container" class="space-y-3 p-3.5 bg-purple-50/50 border border-purple-100 rounded-2xl">
                    <h4 class="text-xs font-bold text-purple-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-box text-purple-600"></i> Kargo Takip Bilgileri
                    </h4>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Kargo Firması</label>
                        <select name="shipping_company" class="w-full bg-white border border-gray-200 text-gray-800 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:border-purple-500">
                            @php
                                $companies = ['Yurtiçi Kargo', 'Aras Kargo', 'MNG Kargo', 'Sürat Kargo', 'PTT Kargo', 'Trendyol Express', 'Hepsijet', 'Kolay Gelsin', 'Diğer'];
                            @endphp
                            @foreach($companies as $comp)
                                <option value="{{ $comp }}" {{ ($order->shipping_company ?? 'Yurtiçi Kargo') == $comp ? 'selected' : '' }}>{{ $comp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Kargo Takip Numarası</label>
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Örn: 987654321" 
                               class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-mono font-bold text-gray-800 focus:outline-none focus:border-purple-500">
                    </div>

                    @if($order->tracking_url)
                        <div class="pt-1">
                            <a href="{{ $order->tracking_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-purple-700 hover:underline">
                                <i class="fa-solid fa-external-link text-[10px]"></i> Kargo Takip Sayfasına Git
                            </a>
                        </div>
                    @endif
                </div>
                
                <button type="submit" class="w-full bg-afiDark hover:bg-gray-800 text-white font-bold py-2.5 rounded-xl transition text-xs flex justify-center items-center gap-2 shadow-md">
                    <i class="fa-solid fa-floppy-disk"></i> Değişiklikleri Kaydet
                </button>
                <p class="text-[11px] text-gray-400 text-center"><i class="fa-solid fa-bell text-amber-500"></i> Kargoya verildiğinde müşteriye SMS gönderilir.</p>
            </form>
        </div>
    </div>

    <!-- Ürün Listesi Tablosu -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h2 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-boxes-stacked text-adminAccent"></i> Sipariş Verilen Ürünler ({{ count($order->items) }})
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-[11px] uppercase font-bold tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Ürün Detayı</th>
                        <th class="py-3.5 px-6 text-center">Birim Fiyat</th>
                        <th class="py-3.5 px-6 text-center">Adet</th>
                        <th class="py-3.5 px-6 text-right">Toplam Tutar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-center shrink-0 p-1">
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
                                    <p class="font-bold text-gray-800 text-sm leading-snug">{{ $item->product_name }}</p>
                                    @if($item->product)
                                        <a href="{{ route('admin.products.edit', $item->product->id) }}" target="_blank" class="text-xs text-amber-600 hover:text-amber-700 font-semibold inline-flex items-center gap-1 mt-1">
                                            Ürün Düzenleme Sayfası <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                        </a>
                                    @else
                                        <span class="text-xs text-rose-500 font-semibold mt-1 inline-block">Silinmiş Ürün</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center font-semibold text-gray-700">
                            {{ number_format($item->price, 2, ',', '.') }} ₺
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-gray-900">
                            x{{ $item->quantity }}
                        </td>
                        <td class="py-4 px-6 text-right font-black text-gray-900 text-base">
                            {{ number_format($item->price * $item->quantity, 2, ',', '.') }} ₺
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    {{-- ════ BİLDİRİM SİMÜLASYON LOG PANELİ ════ --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-bell text-amber-500"></i> Bildirim Simülasyon Logu
                <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full border border-amber-200">SİMÜLASYON</span>
            </h2>
            <button onclick="clearNotifLog()" class="text-xs text-gray-400 hover:text-red-500 transition flex items-center gap-1">
                <i class="fa-solid fa-trash-can text-[11px]"></i> Logu Temizle
            </button>
        </div>
        <div id="notif-log-container" class="p-5 space-y-3 max-h-72 overflow-y-auto">
            {{-- Başlangıç kaydı --}}
            <div class="notif-log-entry flex gap-3 p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 text-xs">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-emerald-800">Sipariş Oluşturuldu — E-posta Gönderildi <span class="font-mono font-normal text-emerald-600">[SİMÜLE]</span></p>
                    <p class="text-[11px] text-emerald-700 mt-0.5">
                        Konu: <b>Siparişiniz Alındı — {{ $order->reference_code ?? 'ORD-'.$order->id }}</b><br>
                        Alıcı: <b>{{ $order->user->email ?? 'misafir@example.com' }}</b>
                    </p>
                    <p class="text-[10px] text-emerald-500 mt-1">{{ $order->created_at->format('d M Y, H:i:s') }}</p>
                </div>
            </div>

            @if(in_array($order->status, ['Kargoya Verildi', 'Kargolandı', 'Tamamlandı']))
            <div class="notif-log-entry flex gap-3 p-3 rounded-2xl bg-purple-50 border border-purple-100">
                <div class="w-8 h-8 rounded-xl bg-purple-500 text-white flex items-center justify-center shrink-0 text-xs">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-purple-800">Kargo Bildirimi — SMS + E-posta <span class="font-mono font-normal text-purple-600">[SİMÜLE]</span></p>
                    <p class="text-[11px] text-purple-700 mt-0.5">
                        Konu: <b>Siparişiniz Kargoya Verildi</b><br>
                        @if($order->tracking_number) Takip No: <b class="font-mono">{{ $order->tracking_number }}</b> ({{ $order->shipping_company ?? 'Kargo' }}) @endif
                    </p>
                    <p class="text-[10px] text-purple-500 mt-1">{{ $order->updated_at->format('d M Y, H:i:s') }}</p>
                </div>
            </div>
            @endif

            @if($order->status === 'Tamamlandı')
            <div class="notif-log-entry flex gap-3 p-3 rounded-2xl bg-blue-50 border border-blue-100">
                <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0 text-xs">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-blue-800">Değerlendirme Daveti — E-posta <span class="font-mono font-normal text-blue-600">[SİMÜLE]</span></p>
                    <p class="text-[11px] text-blue-700 mt-0.5">
                        Konu: <b>Deneyiminizi Paylaşın — Afi Bilişim</b><br>
                        Alıcı: <b>{{ $order->user->email ?? 'misafir@example.com' }}</b>
                    </p>
                    <p class="text-[10px] text-blue-500 mt-1">{{ $order->updated_at->format('d M Y, H:i:s') }}</p>
                </div>
            </div>
            @endif

            @if($order->status === 'İptal Edildi')
            <div class="notif-log-entry flex gap-3 p-3 rounded-2xl bg-rose-50 border border-rose-100">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 text-xs">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-rose-800">İptal Bildirimi — E-posta <span class="font-mono font-normal text-rose-600">[SİMÜLE]</span></p>
                    <p class="text-[11px] text-rose-700 mt-0.5">
                        Konu: <b>Siparişiniz İptal Edildi — {{ $order->reference_code ?? 'ORD-'.$order->id }}</b><br>
                        Alıcı: <b>{{ $order->user->email ?? 'misafir@example.com' }}</b>
                    </p>
                    <p class="text-[10px] text-rose-500 mt-1">{{ $order->updated_at->format('d M Y, H:i:s') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ════ ADMIN BİLDİRİM TOAST ════ --}}
<div id="admin-notif-toast"
     style="position:fixed; bottom:2rem; right:2rem; z-index:9999; min-width:320px; max-width:420px; display:none; transform:translateY(20px); opacity:0; transition:all 0.35s cubic-bezier(0.16,1,0.3,1);">
</div>

<script>
/* ── Status form → AJAX ── */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="update-status"]');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Kaydediliyor...';

        try {
            const fd = new FormData(form);
            const resp = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': fd.get('_token') },
                body: fd
            });
            const data = await resp.json();

            if (resp.ok && data.success) {
                addNotifLogEntry(data.status, data.sms_sent, data.tracking_number, data.shipping_company);
                showAdminToast('success', data.status, data.sms_sent);
            } else {
                showAdminToast('error', data.message || 'Güncelleme başarısız.');
            }
        } catch (err) {
            showAdminToast('error', 'Sunucu hatası oluştu.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    });
});

const statusConfig = {
    'Onaylandı':      { icon: 'fa-check-circle',  color: 'emerald', label: 'Sipariş Onaylandı',     emailSubject: 'Siparişiniz Onaylandı' },
    'Kargoya Verildi':{ icon: 'fa-truck-fast',     color: 'purple',  label: 'Kargoya Verildi',       emailSubject: 'Siparişiniz Kargoya Verildi' },
    'Tamamlandı':     { icon: 'fa-star',           color: 'blue',    label: 'Sipariş Tamamlandı',    emailSubject: 'Siparişiniz Tamamlandı' },
    'İptal Edildi':   { icon: 'fa-ban',            color: 'rose',    label: 'Sipariş İptal Edildi',  emailSubject: 'Siparişiniz İptal Edildi' },
    'Bekliyor':       { icon: 'fa-clock',          color: 'amber',   label: 'Beklemeye Alındı',      emailSubject: 'Sipariş Durumu Güncellendi' },
};

function addNotifLogEntry(status, smsSent, trackingNo, shippingCo) {
    const cfg = statusConfig[status] || { icon: 'fa-bell', color: 'gray', label: status, emailSubject: 'Durum Güncellendi' };
    const colorMap = {
        emerald: 'bg-emerald-50 border-emerald-100 bg-emerald-500 text-emerald-800 text-emerald-700 text-emerald-500',
        purple:  'bg-purple-50  border-purple-100  bg-purple-500  text-purple-800  text-purple-700  text-purple-500',
        blue:    'bg-blue-50    border-blue-100    bg-blue-500    text-blue-800    text-blue-700    text-blue-500',
        rose:    'bg-rose-50    border-rose-100    bg-rose-500    text-rose-800    text-rose-700    text-rose-500',
        amber:   'bg-amber-50   border-amber-100   bg-amber-500   text-amber-800   text-amber-700   text-amber-500',
        gray:    'bg-gray-50    border-gray-100    bg-gray-500    text-gray-800    text-gray-700    text-gray-500',
    };
    const now = new Date().toLocaleString('tr-TR');
    const smsNote = smsSent ? '<span class="ml-1 bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold text-[10px]">✓ SMS Gönderildi</span>' : '';
    const trackNote = trackingNo ? `<br>Takip: <b class="font-mono">${trackingNo}</b> (${shippingCo || 'Kargo'})` : '';

    const entry = document.createElement('div');
    entry.className = `notif-log-entry flex gap-3 p-3 rounded-2xl bg-${cfg.color}-50 border border-${cfg.color}-100`;
    entry.style.cssText = 'opacity:0;transform:translateY(-8px);transition:all 0.4s ease;';
    entry.innerHTML = `
        <div class="w-8 h-8 rounded-xl bg-${cfg.color}-500 text-white flex items-center justify-center shrink-0 text-xs">
            <i class="fa-solid ${cfg.icon}"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-${cfg.color}-800">${cfg.label} — E-posta ${smsNote} <span class="font-mono font-normal text-${cfg.color}-600">[SİMÜLE]</span></p>
            <p class="text-[11px] text-${cfg.color}-700 mt-0.5">Konu: <b>${cfg.emailSubject}</b>${trackNote}</p>
            <p class="text-[10px] text-${cfg.color}-500 mt-1">${now}</p>
        </div>`;

    const container = document.getElementById('notif-log-container');
    container.prepend(entry);
    requestAnimationFrame(() => { entry.style.opacity = '1'; entry.style.transform = 'translateY(0)'; });
}

function showAdminToast(type, statusOrMsg, smsSent) {
    const toast = document.getElementById('admin-notif-toast');
    const isSuccess = type === 'success';
    const cfg = statusConfig[statusOrMsg] || {};
    const emoji = { 'Onaylandı':'✅', 'Kargoya Verildi':'🚚', 'Tamamlandı':'🎉', 'İptal Edildi':'❌', 'Bekliyor':'⏳' };

    toast.innerHTML = isSuccess ? `
        <div style="background:#fff;border:1.5px solid #d1fae5;border-radius:1rem;padding:1rem 1.25rem;box-shadow:0 20px 40px rgba(0,0,0,0.15);display:flex;gap:12px;align-items:flex-start;">
            <div style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#10b981,#059669);display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
            <div>
                <p style="font-weight:800;color:#064e3b;font-size:13px;margin:0 0 2px;">${emoji[statusOrMsg] || '📬'} Durum Güncellendi: ${statusOrMsg}</p>
                <p style="font-size:11px;color:#047857;margin:0;">Müşteriye bildirim simüle edildi.${smsSent ? ' <b>SMS gönderildi.</b>' : ''}</p>
            </div>
        </div>` :
        `<div style="background:#fff;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.25rem;box-shadow:0 20px 40px rgba(0,0,0,0.15);display:flex;gap:12px;align-items:center;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:20px;"></i>
            <p style="font-weight:700;color:#7f1d1d;font-size:13px;margin:0;">${statusOrMsg}</p>
        </div>`;

    toast.style.display = 'block';
    requestAnimationFrame(() => { toast.style.opacity = '1'; toast.style.transform = 'translateY(0)'; });
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => { toast.style.display = 'none'; }, 350);
    }, 4000);
}

function clearNotifLog() {
    if (!confirm('Log kayıtları temizlensin mi?')) return;
    document.getElementById('notif-log-container').innerHTML =
        '<p class="text-xs text-gray-400 text-center py-6"><i class="fa-solid fa-inbox mr-1"></i>Log boş. Durum güncelleyince kayıtlar burada görünür.</p>';
}

function toggleCargoInputs(val) {}
</script>
@endsection
