@extends('admin.layouts.app')

@section('title', 'Destek & Teknik Servis Talepleri | Afi Bilişim Admin')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white mb-1 flex items-center gap-2">
            <i class="fa-solid fa-headset text-adminYellow"></i> Destek ve Teknik Servis Talepleri
        </h1>
        <p class="text-slate-400 text-sm">Müşterilerden gelen iletişim ve teknik servis başvurularını buradan yönetebilirsiniz.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm">
    <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
</div>
@endif

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Toplam Talep -->
    <a href="{{ route('admin.service-requests.index', request()->except('status')) }}" 
       class="bg-adminCard p-5 rounded-2xl border transition-all shadow-lg hover:border-slate-500 flex items-center justify-between {{ !request('status') ? 'border-adminYellow ring-1 ring-adminYellow' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Toplam Başvuru</p>
            <p class="text-2xl font-black text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-slate-800 flex items-center justify-center text-slate-400">
            <i class="fa-solid fa-inbox text-xl"></i>
        </div>
    </a>

    <!-- Yeni -->
    <a href="{{ route('admin.service-requests.index', array_merge(request()->except('status'), ['status' => 'Yeni'])) }}" 
       class="bg-adminCard p-5 rounded-2xl border transition-all shadow-lg hover:border-amber-500 flex items-center justify-between {{ request('status') == 'Yeni' ? 'border-amber-500 ring-1 ring-amber-500 bg-amber-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-clock"></i> Yeni Talepler
            </p>
            <p class="text-2xl font-black text-amber-400">{{ $stats['new'] }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
            <i class="fa-solid fa-bell text-xl"></i>
        </div>
    </a>

    <!-- İnceleniyor -->
    <a href="{{ route('admin.service-requests.index', array_merge(request()->except('status'), ['status' => 'İnceleniyor'])) }}" 
       class="bg-adminCard p-5 rounded-2xl border transition-all shadow-lg hover:border-blue-500 flex items-center justify-between {{ request('status') == 'İnceleniyor' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-screwdriver-wrench"></i> İnceleniyor
            </p>
            <p class="text-2xl font-black text-blue-400">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
            <i class="fa-solid fa-spinner text-xl"></i>
        </div>
    </a>

    <!-- Çözüldü / Tamamlandı -->
    <a href="{{ route('admin.service-requests.index', array_merge(request()->except('status'), ['status' => 'Çözüldü / Tamamlandı'])) }}" 
       class="bg-adminCard p-5 rounded-2xl border transition-all shadow-lg hover:border-emerald-500 flex items-center justify-between {{ request('status') == 'Çözüldü / Tamamlandı' ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> Çözüldü / Tamamlandı
            </p>
            <p class="text-2xl font-black text-emerald-400">{{ $stats['completed'] }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-xl"></i>
        </div>
    </a>
</div>

<!-- Filters Bar -->
<div class="bg-adminCard p-4 rounded-2xl border border-adminBorder shadow-md mb-6">
    <form action="{{ route('admin.service-requests.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
        <!-- Search -->
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Müşteri adı, telefon, e-posta, cihaz modeli veya talep detayı..." class="w-full bg-slate-800 border border-adminBorder rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:outline-none focus:border-adminYellow transition-colors">
            <i class="fa-solid fa-search absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
        </div>

        <!-- Status Filter -->
        <div class="w-full md:w-52">
            <select name="status" onchange="this.form.submit()" class="w-full bg-slate-800 border border-adminBorder rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-adminYellow transition-colors">
                <option value="">-- Tüm Durumlar --</option>
                <option value="Yeni" {{ request('status') == 'Yeni' ? 'selected' : '' }}>Yeni Talepler</option>
                <option value="İnceleniyor" {{ request('status') == 'İnceleniyor' ? 'selected' : '' }}>İnceleniyor</option>
                <option value="Çözüldü / Tamamlandı" {{ request('status') == 'Çözüldü / Tamamlandı' ? 'selected' : '' }}>Çözüldü / Tamamlandı</option>
                <option value="İptal Edildi" {{ request('status') == 'İptal Edildi' ? 'selected' : '' }}>İptal Edildi</option>
            </select>
        </div>

        <!-- Submit & Clear -->
        <button type="submit" class="bg-adminYellow hover:bg-yellow-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-1.5 whitespace-nowrap">
            <i class="fa-solid fa-filter"></i> Filtrele
        </button>
        @if(request()->hasAny(['search', 'status', 'service_type']))
            <a href="{{ route('admin.service-requests.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-1.5 whitespace-nowrap">
                <i class="fa-solid fa-xmark"></i> Temizle
            </a>
        @endif
    </form>
</div>

<!-- Requests Table Card -->
<div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-adminBorder">
                <tr>
                    <th class="px-6 py-4">ID / Tarih</th>
                    <th class="px-6 py-4">Müşteri Bilgileri</th>
                    <th class="px-6 py-4">Hizmet / Cihaz</th>
                    <th class="px-6 py-4">Sorun & Açıklama</th>
                    <th class="px-6 py-4">Durum</th>
                    <th class="px-6 py-4 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-adminBorder">
                @forelse($requests as $req)
                <tr class="hover:bg-slate-800/40 transition-colors {{ in_array($req->status, ['Yeni', 'pending']) ? 'bg-amber-950/10' : '' }}">
                    <!-- ID & Date -->
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-bold text-slate-300">#SR-{{ $req->id }}</span>
                        <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                            <i class="fa-regular fa-clock"></i> {{ $req->created_at ? $req->created_at->format('d.m.Y H:i') : '-' }}
                        </p>
                    </td>

                    <!-- Customer Info -->
                    <td class="px-6 py-4">
                        <p class="font-bold text-white leading-snug flex items-center gap-1.5">
                            <i class="fa-solid fa-user text-slate-500 text-xs"></i> {{ $req->name }}
                        </p>
                        <div class="text-xs text-slate-400 mt-1 space-y-0.5">
                            <p class="flex items-center gap-1.5 font-mono text-slate-300">
                                <i class="fa-solid fa-phone text-slate-500 text-[10px]"></i> {{ $req->phone }}
                            </p>
                            @if($req->email)
                                <p class="flex items-center gap-1.5 text-slate-400 truncate max-w-[200px]" title="{{ $req->email }}">
                                    <i class="fa-regular fa-envelope text-slate-500 text-[10px]"></i> {{ $req->email }}
                                </p>
                            @endif
                        </div>
                    </td>

                    <!-- Service & Device -->
                    <td class="px-6 py-4">
                        <span class="bg-slate-800 border border-slate-700 text-slate-200 px-2.5 py-1 rounded-lg text-xs font-bold inline-block mb-1">
                            <i class="fa-solid fa-microchip text-adminYellow text-[10px] mr-1"></i> {{ $req->service_type }}
                        </span>
                        @if($req->device_model)
                            <p class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                <i class="fa-solid fa-laptop text-slate-500 text-[10px]"></i> {{ $req->device_model }}
                            </p>
                        @endif
                    </td>

                    <!-- Problem Description snippet -->
                    <td class="px-6 py-4">
                        <p class="text-xs text-slate-300 leading-relaxed line-clamp-2 max-w-xs" title="{{ $req->description }}">
                            {{ $req->description ?: 'Açıklama belirtilmemiş.' }}
                        </p>
                        @if($req->admin_note)
                            <p class="text-[11px] text-amber-400 font-medium mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-sticky-note"></i> Not: {{ \Illuminate\Support\Str::limit($req->admin_note, 35) }}
                            </p>
                        @endif
                    </td>

                    <!-- Status Selector Dropdown -->
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.service-requests.status', $req->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('POST')
                            <select name="status" onchange="this.form.submit()" 
                                    class="bg-slate-900 border text-xs font-bold rounded-xl px-3 py-1.5 focus:outline-none transition-colors cursor-pointer
                                    {{ in_array($req->status, ['Yeni', 'pending']) ? 'border-amber-500/50 text-amber-400 bg-amber-500/10' : '' }}
                                    {{ in_array($req->status, ['İnceleniyor', 'in-progress']) ? 'border-blue-500/50 text-blue-400 bg-blue-500/10' : '' }}
                                    {{ in_array($req->status, ['Çözüldü / Tamamlandı', 'Tamamlandı', 'completed']) ? 'border-emerald-500/50 text-emerald-400 bg-emerald-500/10' : '' }}
                                    {{ in_array($req->status, ['İptal Edildi', 'cancelled']) ? 'border-rose-500/50 text-rose-400 bg-rose-500/10' : '' }}">
                                <option value="Yeni" {{ in_array($req->status, ['Yeni', 'pending']) ? 'selected' : '' }}>⏳ Yeni</option>
                                <option value="İnceleniyor" {{ in_array($req->status, ['İnceleniyor', 'in-progress']) ? 'selected' : '' }}>🔍 İnceleniyor</option>
                                <option value="Çözüldü / Tamamlandı" {{ in_array($req->status, ['Çözüldü / Tamamlandı', 'Tamamlandı', 'completed']) ? 'selected' : '' }}>✅ Çözüldü / Tamamlandı</option>
                                <option value="İptal Edildi" {{ in_array($req->status, ['İptal Edildi', 'cancelled']) ? 'selected' : '' }}>❌ İptal Edildi</option>
                            </select>
                        </form>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button type="button" 
                                    onclick="openDetailModal({{ json_encode($req) }})" 
                                    class="px-3 py-1.5 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 hover:bg-blue-600 hover:text-white text-xs font-bold transition flex items-center gap-1"
                                    title="Talep Detayı ve Notlar">
                                <i class="fa-solid fa-eye"></i> Detay
                            </button>

                            <form action="{{ route('admin.service-requests.destroy', $req->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                        <i class="fa-solid fa-headset text-4xl mb-3 text-slate-600"></i>
                        <p class="font-bold text-slate-400">Henüz kayıtlı bir teknik servis veya destek talebi bulunmuyor.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
    <div class="p-4 border-t border-adminBorder">
        {{ $requests->links() }}
    </div>
    @endif
</div>

<!-- TALEP DETAY VE YÖNETİCİ NOTU MODALI -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm hidden opacity-0 transition-all duration-300">
    <div class="bg-adminCard border border-adminBorder rounded-3xl shadow-2xl w-full max-w-2xl transform scale-95 transition-all duration-300 overflow-hidden m-4">
        
        <!-- Modal Header -->
        <div class="bg-slate-800/80 border-b border-adminBorder p-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-adminYellow/20 text-adminYellow flex items-center justify-center font-bold">
                    <i class="fa-solid fa-headset text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white" id="modal-req-title">Talep Detayı</h3>
                    <p class="text-xs text-slate-400" id="modal-req-date"></p>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white text-xl transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="modal-status-form" method="POST" action="">
            @csrf
            @method('POST')
            <div class="p-6 space-y-6">
                
                <!-- Müşteri ve Cihaz Bilgi Kartı -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Müşteri Adı</p>
                        <p class="text-sm font-bold text-white flex items-center gap-1.5" id="modal-customer-name"></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">İletişim Telefonu</p>
                        <p class="text-sm font-bold text-adminYellow font-mono flex items-center gap-1.5" id="modal-customer-phone"></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">E-Posta Adresi</p>
                        <p class="text-sm font-medium text-slate-300" id="modal-customer-email"></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Hizmet / Cihaz</p>
                        <p class="text-sm font-medium text-slate-300" id="modal-service-device"></p>
                    </div>
                </div>

                <!-- Müşteri Sorun Açıklaması -->
                <div>
                    <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-message text-adminYellow"></i> Müşteri Açıklaması / Talep Detayı
                    </h4>
                    <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl text-xs text-slate-200 leading-relaxed font-sans min-h-[90px] whitespace-pre-line" id="modal-description">
                    </div>
                </div>

                <!-- Durum Güncelleme Seçimi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Talep Durumu</label>
                        <select name="status" id="modal-status-select" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-adminYellow transition-colors">
                            <option value="Yeni">⏳ Yeni</option>
                            <option value="İnceleniyor">🔍 İnceleniyor</option>
                            <option value="Çözüldü / Tamamlandı">✅ Çözüldü / Tamamlandı</option>
                            <option value="İptal Edildi">❌ İptal Edildi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Hızlı İletişim</label>
                        <div class="flex gap-2">
                            <a id="modal-whatsapp-btn" href="#" target="_blank" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-3 rounded-xl text-xs text-center transition flex items-center justify-center gap-1">
                                <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                            </a>
                            <a id="modal-call-btn" href="#" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-3 rounded-xl text-xs text-center transition flex items-center justify-center gap-1">
                                <i class="fa-solid fa-phone text-xs"></i> Ara
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Yönetici Notu -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span><i class="fa-solid fa-pen-to-square text-amber-400"></i> Yönetici / Servis Notu</span>
                        <span class="text-[10px] text-slate-500 font-normal">(İsteğe bağlı, sadece admin panelinde görünür)</span>
                    </label>
                    <textarea name="admin_note" id="modal-admin-note" rows="3" placeholder="Örn: Müşteri arandı, yedek parça siparişi verildi, yarın teslim edilecek..." class="w-full bg-slate-900 border border-slate-800 rounded-2xl p-3 text-xs text-white focus:outline-none focus:border-adminYellow transition-colors"></textarea>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-800/80 border-t border-adminBorder p-4 px-6 flex justify-end gap-3">
                <button type="button" onclick="closeDetailModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800 transition">İptal</button>
                <button type="submit" class="bg-adminYellow hover:bg-yellow-400 text-slate-950 font-black px-6 py-2.5 rounded-xl text-xs transition shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDetailModal(req) {
        document.getElementById('modal-req-title').innerText = 'Talep #' + req.id + ' Detayı';
        document.getElementById('modal-req-date').innerText = 'Başvuru Tarihi: ' + (req.created_at ? new Date(req.created_at).toLocaleString('tr-TR') : '-');
        
        document.getElementById('modal-customer-name').innerText = req.name || '-';
        document.getElementById('modal-customer-phone').innerText = req.phone || '-';
        document.getElementById('modal-customer-email').innerText = req.email || 'Belirtilmedi';
        
        let deviceText = req.service_type || 'Teknik Servis';
        if (req.device_model) {
            deviceText += ' (' + req.device_model + ')';
        }
        document.getElementById('modal-service-device').innerText = deviceText;
        
        document.getElementById('modal-description').innerText = req.description || 'Açıklama belirtilmemiş.';
        document.getElementById('modal-admin-note').value = req.admin_note || '';

        // Form action setting
        document.getElementById('modal-status-form').action = '/admin/service-requests/' + req.id + '/status';

        // Select correct status
        const select = document.getElementById('modal-status-select');
        let mappedStatus = req.status;
        if (req.status === 'pending') mappedStatus = 'Yeni';
        if (req.status === 'in-progress') mappedStatus = 'İnceleniyor';
        if (req.status === 'completed') mappedStatus = 'Çözüldü / Tamamlandı';
        select.value = mappedStatus;

        // WhatsApp & Call button links
        let cleanPhone = (req.phone || '').replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) {
            cleanPhone = '9' + cleanPhone;
        } else if (!cleanPhone.startsWith('90')) {
            cleanPhone = '90' + cleanPhone;
        }
        document.getElementById('modal-whatsapp-btn').href = 'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent('Merhaba ' + req.name + ', Afi Bilişim Destek/Servis talebiniz hakkında iletişime geçiyoruz.');
        document.getElementById('modal-call-btn').href = 'tel:' + (req.phone || '');

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
