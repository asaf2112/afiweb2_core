@extends('admin.layouts.app')

@section('title', 'Slider / Banner Yönetimi | Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-800/80 p-6 rounded-2xl border border-slate-700/80 backdrop-blur-md">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-3">
                <i class="fa-solid fa-images text-adminYellow"></i> Slider / Banner Yönetimi
            </h1>
            <p class="text-sm text-slate-400 mt-1">Anasayfa hero slider slaytlarını ekleyin, düzenleyin ve aktif/pasif durumlarını yönetin.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="bg-adminYellow hover:bg-yellow-400 text-slate-950 font-bold px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 text-sm shadow-lg shadow-yellow-500/20 shrink-0 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> Yeni Slider Ekle
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-5 py-4 rounded-xl flex items-center gap-3 text-sm font-semibold">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Banners Table -->
    <div class="bg-slate-800/60 border border-slate-700/80 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/80 text-xs uppercase font-bold text-slate-400 border-b border-slate-700/80">
                    <tr>
                        <th class="px-6 py-4">Sıra</th>
                        <th class="px-6 py-4">Slayt Başlığı & Rozet</th>
                        <th class="px-6 py-4">Tema / Glow</th>
                        <th class="px-6 py-4">Kart Ürünü & Fiyat</th>
                        <th class="px-6 py-4">Kart Butonu</th>
                        <th class="px-6 py-4">Durum</th>
                        <th class="px-6 py-4 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4 font-mono font-bold text-white">
                                <span class="bg-slate-900 border border-slate-700 px-2.5 py-1 rounded-lg text-xs">#{{ $banner->order }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-base group-hover:text-adminYellow transition-colors">
                                    {{ $banner->title }} <span class="text-amber-400 font-extrabold">{{ $banner->subtitle }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($banner->top_badge_text)
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-900 border border-slate-700 text-slate-300">
                                            {{ $banner->top_badge_text }}
                                        </span>
                                    @endif
                                    <span class="text-xs text-slate-400 font-mono truncate max-w-xs">{{ \Illuminate\Support\Str::limit($banner->button_url, 30) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold capitalize border 
                                    @if($banner->glow_color == 'amber') bg-amber-500/10 text-amber-400 border-amber-500/30
                                    @elseif($banner->glow_color == 'cyan') bg-cyan-500/10 text-cyan-400 border-cyan-500/30
                                    @elseif($banner->glow_color == 'blue') bg-blue-500/10 text-blue-400 border-blue-500/30
                                    @elseif($banner->glow_color == 'red') bg-red-500/10 text-red-400 border-red-500/30
                                    @elseif($banner->glow_color == 'emerald') bg-emerald-500/10 text-emerald-400 border-emerald-500/30
                                    @else bg-purple-500/10 text-purple-400 border-purple-500/30 @endif">
                                    <span class="w-2 h-2 rounded-full 
                                        @if($banner->glow_color == 'amber') bg-amber-400
                                        @elseif($banner->glow_color == 'cyan') bg-cyan-400
                                        @elseif($banner->glow_color == 'blue') bg-blue-400
                                        @elseif($banner->glow_color == 'red') bg-red-400
                                        @elseif($banner->glow_color == 'emerald') bg-emerald-400
                                        @else bg-purple-400 @endif"></span>
                                    {{ $banner->glow_color }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-200 text-xs truncate max-w-xs">{{ $banner->card_title ?? '-' }}</div>
                                @if($banner->card_price)
                                    <div class="text-xs font-bold text-amber-400 mt-0.5">
                                        {{ $banner->card_price }} 
                                        @if($banner->card_old_price)
                                            <span class="text-slate-500 line-through text-[11px] font-normal ml-1">{{ $banner->card_old_price }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($banner->card_button_text)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-lg bg-amber-400/10 border border-amber-400/30 text-amber-400">
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i> {{ $banner->card_button_text }}
                                    </span>
                                @else
                                    <span class="text-slate-500 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.banners.toggle-active', $banner->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold cursor-pointer transition-all border {{ $banner->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/20' : 'bg-slate-700/40 text-slate-400 border-slate-600/40 hover:bg-slate-700' }}">
                                        <i class="fa-solid {{ $banner->is_active ? 'fa-check' : 'fa-xmark' }}"></i>
                                        {{ $banner->is_active ? 'Aktif' : 'Pasif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="p-2 bg-slate-700 hover:bg-adminYellow hover:text-slate-950 text-slate-300 rounded-lg transition-colors text-xs font-bold" title="Düzenle">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Bu slider öğesini silmek istediğinize emin misiniz?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-700 hover:bg-red-500 text-slate-300 hover:text-white rounded-lg transition-colors text-xs font-bold border-none cursor-pointer" title="Sil">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-images text-4xl mb-3 text-slate-600"></i>
                                <p class="text-base font-semibold">Henüz hiç slider eklenmemiş.</p>
                                <p class="text-xs text-slate-400 mt-1">Yarıda kalmamak için ilk slaytınızı oluşturun.</p>
                                <a href="{{ route('admin.banners.create') }}" class="inline-block mt-4 bg-adminYellow text-slate-950 font-bold px-4 py-2 rounded-xl text-xs">
                                    İlk Slider'ı Ekle
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="p-4 border-t border-slate-700/80">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
