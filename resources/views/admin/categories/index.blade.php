@extends('admin.layouts.app')

@section('title', 'Kategori Yönetimi | Afi Bilişim')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">Kategoriler</h1>
        <p class="text-slate-400 text-sm">Mağazanızdaki ürün kategorilerini buradan yönetebilirsiniz.</p>
    </div>
    <button class="bg-[#eab308] hover:bg-yellow-400 text-slate-900 font-bold py-2.5 px-5 rounded-xl transition-colors shadow-lg shadow-yellow-500/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Yeni Kategori
    </button>
</div>

<div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg overflow-hidden">
    @if(isset($categories) && $categories->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-adminBorder">
                <tr>
                    <th class="px-6 py-4 font-semibold">Kategori Adı</th>
                    <th class="px-6 py-4 font-semibold">Kısa URL (Slug)</th>
                    <th class="px-6 py-4 font-semibold">Ürün Sayısı</th>
                    <th class="px-6 py-4 text-right font-semibold">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-b border-adminBorder hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-adminYellow border border-adminBorder">
                                <i class="fa-solid fa-folder"></i>
                            </div>
                            <span class="font-bold text-white">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-800 text-adminYellow px-2.5 py-1 rounded-md text-xs font-bold border border-adminBorder">
                            {{ $category->products_count ?? 0 }} Ürün
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 hover:text-white hover:bg-slate-600 inline-flex items-center justify-center transition-colors" title="Düzenle">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white inline-flex items-center justify-center transition-colors" title="Sil">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-12 text-center text-slate-500">
        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-adminBorder text-3xl">
            <i class="fa-solid fa-folder-open text-slate-600"></i>
        </div>
        <h3 class="text-white font-bold text-lg mb-1">Henüz Kategori Yok</h3>
        <p class="mb-6">Sistemde ekli herhangi bir kategori bulunamadı.</p>
        <button class="bg-slate-700 hover:bg-slate-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
            İlk Kategoriyi Oluştur
        </button>
    </div>
    @endif
</div>
@endsection
