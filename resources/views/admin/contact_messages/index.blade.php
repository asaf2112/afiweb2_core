@extends('admin.layouts.app')

@section('title', 'Gelen İletişim Mesajları | Afi Bilişim')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-white mb-1 flex items-center gap-3">
            <i class="fa-solid fa-envelope-open-text text-adminYellow"></i> Gelen İletişim Mesajları
        </h1>
        <p class="text-slate-400 text-sm">Sitedeki iletişim formundan müşteriler tarafından gönderilen mesajların listesi.</p>
    </div>
    <div>
        <span class="bg-slate-800 text-slate-300 border border-adminBorder px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-inbox text-adminYellow"></i> Toplam: {{ $messages->count() }} Mesaj
        </span>
    </div>
</div>

@if(session('success'))
<div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-xl mb-6 text-sm flex items-center gap-3 font-medium">
    <i class="fa-solid fa-circle-check text-lg"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="bg-adminCard rounded-2xl border border-adminBorder shadow-xl overflow-hidden">
    @if(isset($messages) && $messages->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/80 border-b border-adminBorder">
                <tr>
                    <th class="px-6 py-4 font-bold">Ad Soyad</th>
                    <th class="px-6 py-4 font-bold">E-Posta</th>
                    <th class="px-6 py-4 font-bold">Konu</th>
                    <th class="px-6 py-4 font-bold">Mesaj</th>
                    <th class="px-6 py-4 font-bold">Tarih</th>
                    <th class="px-6 py-4 font-bold text-center">Durum</th>
                    <th class="px-6 py-4 text-right font-bold">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-adminBorder">
                @foreach($messages as $msg)
                <tr class="hover:bg-slate-800/40 transition-colors {{ $msg->is_read ? 'opacity-80' : 'bg-yellow-500/5 font-semibold' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-adminYellow font-black text-sm border border-adminBorder shadow-sm">
                                {{ strtoupper(substr($msg->name, 0, 1)) }}
                            </div>
                            <span class="font-bold text-white">{{ $msg->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="mailto:{{ $msg->email }}" class="text-cyan-400 hover:underline flex items-center gap-1.5">
                            <i class="fa-regular fa-envelope text-xs"></i> {{ $msg->email }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-200 font-medium">
                        <span class="bg-slate-800 px-3 py-1 rounded-lg border border-adminBorder text-xs text-adminYellow">
                            {{ $msg->subject }}
                        </span>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="line-clamp-2 text-slate-300 text-xs leading-relaxed" title="{{ $msg->message }}">
                            {{ $msg->message }}
                        </p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                        <i class="fa-regular fa-clock mr-1 text-slate-500"></i> {{ $msg->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($msg->is_read)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700">
                                <i class="fa-solid fa-check-double text-green-400"></i> Okundu
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                <i class="fa-solid fa-circle text-[8px] animate-pulse"></i> Yeni
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Show Detail Modal Trigger -->
                            <button type="button" onclick="openMsgModal('{{ addslashes($msg->name) }}', '{{ addslashes($msg->email) }}', '{{ addslashes($msg->subject) }}', '{{ addslashes(str_replace(["\r", "\n"], [' ', ' '], $msg->message)) }}', '{{ $msg->created_at->format('d.m.Y H:i') }}')" class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white inline-flex items-center justify-center transition-colors" title="Oku / Detay">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>

                            <!-- Toggle Read -->
                            <form action="{{ route('contact-messages.toggle-read', $msg->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-slate-950 inline-flex items-center justify-center transition-colors" title="{{ $msg->is_read ? 'Okunmadı İşaretle' : 'Okundu İşaretle' }}">
                                    <i class="fa-solid {{ $msg->is_read ? 'fa-envelope' : 'fa-envelope-open' }} text-xs"></i>
                                </button>
                            </form>

                            <!-- Delete -->
                            <form action="{{ route('contact-messages.destroy', $msg->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white inline-flex items-center justify-center transition-colors" title="Sil">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-16 text-center text-slate-500">
        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-adminBorder text-3xl text-slate-600 shadow-inner">
            <i class="fa-regular fa-envelope"></i>
        </div>
        <h3 class="text-white font-bold text-lg mb-1">Gelen İletişim Mesajı Yok</h3>
        <p class="text-slate-400 text-sm max-w-md mx-auto">Henüz iletişim formundan gönderilen bir mesaj bulunmuyor.</p>
    </div>
    @endif
</div>

<!-- Message Detail Modal -->
<div id="msgModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-adminCard border border-adminBorder rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
        <button onclick="closeMsgModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-adminYellow/10 text-adminYellow flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-envelope-open"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white" id="modalSubject">Mesaj Detayı</h3>
                <p class="text-xs text-slate-400" id="modalDate"></p>
            </div>
        </div>
        <div class="space-y-4 text-sm">
            <div class="bg-slate-800/60 p-4 rounded-xl border border-adminBorder">
                <p class="text-xs text-slate-400 mb-1 font-semibold uppercase">Gönderen</p>
                <p class="font-bold text-white" id="modalName"></p>
                <p class="text-xs text-cyan-400" id="modalEmail"></p>
            </div>
            <div class="bg-slate-800/60 p-4 rounded-xl border border-adminBorder">
                <p class="text-xs text-slate-400 mb-2 font-semibold uppercase">Mesaj İçeriği</p>
                <p class="text-slate-200 whitespace-pre-line leading-relaxed" id="modalBody"></p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeMsgModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-sm transition-colors">
                Kapat
            </button>
        </div>
    </div>
</div>

<script>
function openMsgModal(name, email, subject, message, date) {
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalEmail').innerText = email;
    document.getElementById('modalSubject').innerText = subject;
    document.getElementById('modalBody').innerText = message;
    document.getElementById('modalDate').innerText = date;
    document.getElementById('msgModal').classList.remove('hidden');
}

function closeMsgModal() {
    document.getElementById('msgModal').classList.add('hidden');
}
</script>
@endsection
