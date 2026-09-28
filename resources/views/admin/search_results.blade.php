@extends('admin.layouts.app')

@section('title', 'Arama Sonuçları | Afi Bilişim')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white mb-2">Arama Sonuçları: "{{ $query }}"</h1>
    <p class="text-slate-400 text-sm">Ürünler içinde yapılan arama sonuçları aşağıdadır.</p>
</div>

<div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg overflow-hidden">
    @if(isset($products) && $products->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-adminBorder">
                <tr>
                    <th class="px-6 py-4">Görsel</th>
                    <th class="px-6 py-4">Ürün Adı</th>
                    <th class="px-6 py-4">Fiyat</th>
                    <th class="px-6 py-4 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-adminBorder hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4">
                        @if($product->main_image)
                            <img src="{{ asset($product->main_image) }}" class="w-16 h-16 object-contain rounded" alt="{{ $product->title }}">
                        @else
                            <div class="w-16 h-16 rounded bg-slate-700 flex items-center justify-center text-slate-500">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-white">{{ $product->title }}</td>
                    <td class="px-6 py-4 font-medium text-white">{{ number_format($product->price, 2, ',', '.') }} ₺</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 hover:text-white hover:bg-slate-600 inline-flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-12 text-center text-slate-500">
        <i class="fa-solid fa-magnifying-glass text-4xl mb-3 text-slate-600"></i>
        <p>Aradığınız kritere uygun sonuç bulunamadı.</p>
    </div>
    @endif
</div>
@endsection
