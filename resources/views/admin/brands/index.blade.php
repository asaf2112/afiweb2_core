@extends('admin.layouts.app')

@section('title', 'Marka Yönetimi | Afi Bilişim Admin')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-white tracking-tight">Marka Yönetimi</h1>
                <span class="bg-yellow-500/10 text-yellow-400 text-xs font-black px-2.5 py-1 rounded-full border border-yellow-500/20">
                    {{ $totalBrandsCount }} Marka
                </span>
            </div>
            <p class="text-slate-400 text-sm mt-1">HP, TwinMOS, ASUS vb. tüm donanım ve bilgisayar markalarını buradan yönetebilir, logolarını ve ana sayfa vitrinini belirleyebilirsiniz.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('brands.index') }}" target="_blank" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2.5 rounded-xl text-sm font-semibold transition border border-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-yellow-400"></i> Marka Kataloğuna Git
            </a>
            <a href="{{ route('admin.brands.create') }}" class="bg-adminYellow hover:bg-yellow-400 text-slate-900 font-bold py-2.5 px-5 rounded-xl transition shadow-lg shadow-yellow-500/20 flex items-center gap-2 text-sm">
                <i class="fa-solid fa-plus"></i> Yeni Marka Ekle
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-adminCard p-4 rounded-xl border border-adminBorder flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Toplam Marka</p>
                <h3 class="text-2xl font-black text-white mt-1">{{ $totalBrandsCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-lg">
                <i class="fa-solid fa-award"></i>
            </div>
        </div>
        <div class="bg-adminCard p-4 rounded-xl border border-adminBorder flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Aktif Markalar</p>
                <h3 class="text-2xl font-black text-emerald-400 mt-1">{{ $activeBrandsCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>
        <div class="bg-adminCard p-4 rounded-xl border border-adminBorder flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Ana Sayfa Vitrininde</p>
                <h3 class="text-2xl font-black text-yellow-400 mt-1">{{ $featuredBrandsCount }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-400 text-lg">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-adminCard p-4 rounded-xl border border-adminBorder flex flex-col md:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="w-full md:w-auto flex-1 flex items-center gap-3">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Marka adı veya slogan ara..." class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-10 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
            </div>

            <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-300 focus:outline-none focus:border-adminYellow">
                <option value="">Tüm Durumlar</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Sadece Aktifler</option>
                <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Ana Sayfa Vitrinindekiler</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Pasif Olanlar</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-lg text-sm font-semibold transition border border-slate-700">
                Filtrele
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.brands.index') }}" class="text-xs text-rose-400 hover:underline">Temizle</a>
            @endif
        </form>
    </div>

    <!-- Brands Table -->
    <div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg overflow-hidden">
        @if($brands->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-adminBorder">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Marka & Logo</th>
                        <th class="px-6 py-4 font-semibold">Slug (URL)</th>
                        <th class="px-6 py-4 font-semibold">Ürün Sayısı</th>
                        <th class="px-6 py-4 text-center font-semibold">Vitrin</th>
                        <th class="px-6 py-4 text-center font-semibold">Durum</th>
                        <th class="px-6 py-4 text-center font-semibold">Sıra</th>
                        <th class="px-6 py-4 text-right font-semibold">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-adminBorder">
                    @foreach($brands as $brand)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <!-- Marka & Logo -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                    @if($brand->logo)
                                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain filter drop-shadow">
                                    @else
                                        <span class="text-xs font-black text-yellow-400 uppercase">{{ substr($brand->name, 0, 3) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-white text-base">{{ $brand->name }}</span>
                                        @if($brand->badge)
                                            <span class="bg-yellow-500/10 text-yellow-400 text-[10px] font-bold px-2 py-0.5 rounded-full border border-yellow-500/20">
                                                {{ $brand->badge }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 line-clamp-1 max-w-xs mt-0.5">{{ $brand->slogan ?: 'Slogan belirtilmemiş' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Slug -->
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">
                            /marka/{{ $brand->slug }}
                        </td>

                        <!-- Ürün Sayısı -->
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.products.index', ['search' => $brand->name]) }}" class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-yellow-400 px-3 py-1 rounded-lg text-xs font-black border border-adminBorder transition">
                                <i class="fa-solid fa-box text-[10px]"></i>
                                {{ $brand->products_count ?? 0 }} Ürün
                            </a>
                        </td>

                        <!-- Vitrin Toggle -->
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.brands.toggle-featured', $brand->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition flex items-center gap-1 mx-auto cursor-pointer {{ $brand->is_featured ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/40 hover:bg-yellow-500/30' : 'bg-slate-800 text-slate-500 border border-slate-700 hover:text-slate-300' }}" title="Ana Sayfa Vitrini Durumu">
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    {{ $brand->is_featured ? 'Vitrinde' : 'Normal' }}
                                </button>
                            </form>
                        </td>

                        <!-- Aktif / Pasif Toggle -->
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.brands.toggle-active', $brand->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition flex items-center gap-1 mx-auto cursor-pointer {{ $brand->is_active ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/25' : 'bg-rose-500/15 text-rose-400 border border-rose-500/30 hover:bg-rose-500/25' }}" title="Aktif / Pasif Yap">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $brand->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    {{ $brand->is_active ? 'Aktif' : 'Pasif' }}
                                </button>
                            </form>
                        </td>

                        <!-- Sıra -->
                        <td class="px-6 py-4 text-center font-bold text-slate-300">
                            {{ $brand->sort_order }}
                        </td>

                        <!-- İşlemler -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:text-yellow-400 hover:bg-slate-700 inline-flex items-center justify-center transition border border-slate-700" title="Katalogda Gör">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 inline-flex items-center justify-center transition border border-slate-700" title="Düzenle">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('\'{{ $brand->name }}\' markasını silmek istediğinize emin misiniz? (Ürünler silinmeyecek, sadece marka alanı temizlenecektir)')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white inline-flex items-center justify-center transition border border-rose-500/20 cursor-pointer" title="Sil">
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
        <div class="p-4 border-t border-adminBorder">
            {{ $brands->links() }}
        </div>
        @else
        <div class="p-12 text-center text-slate-500">
            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-adminBorder text-3xl">
                <i class="fa-solid fa-award text-slate-600"></i>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Marka Bulunamadı</h3>
            <p class="mb-6">Arama kriterlerinize uygun marka bulunamadı veya henüz eklenmedi.</p>
            <a href="{{ route('admin.brands.create') }}" class="bg-adminYellow hover:bg-yellow-400 text-slate-900 font-bold py-2 px-6 rounded-xl transition shadow-lg shadow-yellow-500/20 inline-flex items-center gap-2 text-sm">
                <i class="fa-solid fa-plus"></i> Yeni Marka Oluştur
            </a>
        </div>
        @endif
    </div>

</div>
@endsection