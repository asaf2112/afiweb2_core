@extends('layouts.app')

@section('title', 'Ürün Karşılaştırma | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-[#0b0f19] text-white py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Decorative Background Blur --}}
    <div class="absolute top-0 left-1/3 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-800/80 pb-6">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="w-10 h-10 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-yellow-500 flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black font-heading tracking-tight text-white">ÜRÜN KARŞILAŞTIRMA ARACI</h1>
                    
                    <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check"></i> {{ count($products) }}/4 Seçili Ürün
                    </span>

                    @if(count($products) > 0)
                        @php $catName = $products->first()->category->name ?? 'Genel'; @endphp
                        <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 text-xs font-bold px-3.5 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-layer-group"></i> Kategori: <strong class="text-white">{{ $catName }}</strong>
                        </span>
                    @endif
                </div>
                <p class="text-gray-400 text-sm">Seçtiğiniz donanımların teknik parametrelerini, soket yapılarını, güç tüketimlerini ve fiyatlarını yan yana kıyaslayın.</p>
            </div>
            
            @if(count($products) > 0)
                <div class="flex items-center gap-3">
                    <button type="button" onclick="clearCompareList()" class="bg-red-500/10 hover:bg-red-500 hover:text-white text-red-400 font-bold py-2.5 px-4 rounded-xl border border-red-500/30 transition text-xs flex items-center gap-2">
                        <i class="fa-solid fa-trash-can"></i> Listeyi Temizle
                    </button>
                    <a href="{{ route('products.index', ['category_id' => $products->first()->category_id ?? '']) }}" class="bg-yellow-500 hover:bg-yellow-400 text-slate-950 font-bold py-2.5 px-4 rounded-xl transition text-xs flex items-center gap-2 shadow-md shadow-yellow-500/20">
                        <i class="fa-solid fa-plus"></i> {{ $catName ?? 'Kategoriye' }} Ürün Ekle
                    </a>
                </div>
            @endif
        </div>

        {{-- Category Warning Notice --}}
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-4 mb-6 flex items-start gap-3 text-xs text-yellow-300">
            <i class="fa-solid fa-circle-info text-yellow-400 text-lg shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold text-yellow-400">Kategori Bazlı Kıyaslama Kuralı:</strong> 
                Karşılaştırma aracı teknik parametrelerin tam ve doğru kıyaslanabilmesi için sadece aynı kategorideki ürünlerin eklenmesine izin verir. Karşılaştırmaya farklı bir kategoriden ürün eklemek isterseniz önce mevcut listeyi temizleyebilirsiniz.
            </div>
        </div>

        @if(count($products) > 0)
            {{-- Compare Matrix Table --}}
            <div class="overflow-x-auto rounded-3xl border border-gray-800 bg-[#12151c] shadow-2xl sidebar-scroll">
                <table class="w-full text-left border-collapse min-w-[750px]">
                    <thead>
                        <tr class="border-b border-gray-800 bg-[#181c24]">
                            <th class="p-5 w-52 text-xs font-bold text-gray-400 uppercase tracking-wider border-r border-gray-800">
                                Ayırt Edici Özellikler
                            </th>
                            @foreach($products as $product)
                                <th class="p-5 w-64 text-center border-r border-gray-800/60 last:border-0 relative group">
                                    <button type="button" 
                                            onclick="removeFromCompare({{ $product->id }})" 
                                            class="absolute top-3 right-3 w-7 h-7 rounded-lg bg-gray-900/80 text-gray-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors border border-gray-700 shadow-sm"
                                            title="Karşılaştırmadan Kaldır">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>

                                    @php
                                        $imageUrl = 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400&q=80';
                                        if ($product->main_image) {
                                            if (file_exists(public_path($product->main_image))) {
                                                $imageUrl = asset($product->main_image);
                                            } elseif (file_exists(public_path('storage/' . $product->main_image))) {
                                                $imageUrl = asset('storage/' . $product->main_image);
                                            } elseif (\Illuminate\Support\Str::startsWith($product->main_image, ['http://', 'https://'])) {
                                                $imageUrl = $product->main_image;
                                            }
                                        }
                                    @endphp
                                    <div class="w-32 h-32 mx-auto mb-3 bg-[#0b0f19] rounded-2xl p-3 border border-gray-800 flex items-center justify-center shadow-inner">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->title }}" class="object-contain max-h-full max-w-full">
                                    </div>
                                    <h3 class="text-sm font-bold text-white leading-snug line-clamp-2 mb-2 min-h-[2.5rem]" title="{{ $product->title }}">
                                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-yellow-400 transition-colors">
                                            {{ $product->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xl font-black text-yellow-400 mb-3">{{ number_format($product->final_price, 2, ',', '.') }} ₺</p>
                                    
                                    <button type="button" 
                                            onclick="addToCartQuick(this, {{ $product->id }})" 
                                            class="w-full bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold py-2.5 px-3 rounded-xl transition text-xs flex items-center justify-center gap-1.5 shadow-md shadow-yellow-500/20">
                                        <i class="fa-solid fa-cart-shopping text-xs"></i> Sepete Ekle
                                    </button>
                                </th>
                            @endforeach
                            
                            {{-- Fill empty columns up to 4 --}}
                            @for($i = count($products); $i < 4; $i++)
                                <th class="p-5 w-64 text-center border-r border-gray-800/60 last:border-0 bg-[#0f121a]/50">
                                    <div class="border-2 border-dashed border-gray-800/80 rounded-2xl p-6 flex flex-col items-center justify-center text-gray-500 min-h-[230px]">
                                        <i class="fa-solid fa-circle-plus text-3xl text-yellow-500/30 mb-3"></i>
                                        <span class="text-xs font-bold text-gray-400">Ürün Ekle</span>
                                        <a href="{{ route('products.index', ['category_id' => $products->first()->category_id ?? '']) }}" class="mt-3 text-[11px] bg-gray-800 hover:bg-gray-700 text-yellow-400 px-3 py-1.5 rounded-lg border border-gray-700 font-semibold transition">
                                            Kataloğa Git
                                        </a>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60 text-xs">
                        
                        {{-- Kategori --}}
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="p-4 font-bold text-gray-400 bg-[#181c24]/50 border-r border-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-tag text-yellow-500/80"></i> Kategori
                            </td>
                            @foreach($products as $product)
                                <td class="p-4 text-center text-gray-200 border-r border-gray-800/60 last:border-0 font-semibold">
                                    <span class="bg-gray-800 text-gray-300 px-2.5 py-1 rounded-md border border-gray-700">
                                        {{ $product->category->name ?? 'Genel' }}
                                    </span>
                                </td>
                            @endforeach
                            @for($i = count($products); $i < 4; $i++)
                                <td class="p-4 border-r border-gray-800/60 last:border-0 bg-[#0f121a]/30"></td>
                            @endfor
                        </tr>

                        {{-- Ürün Durumu --}}
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="p-4 font-bold text-gray-400 bg-[#181c24]/50 border-r border-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-shield-check text-yellow-500/80"></i> Ürün Durumu
                            </td>
                            @foreach($products as $product)
                                <td class="p-4 text-center border-r border-gray-800/60 last:border-0">
                                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $product->condition_type == 'used' ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-blue-500/15 text-blue-400 border border-blue-500/30' }}">
                                        {{ $product->condition_type == 'used' ? 'İkinci El (100% Test Edilmiş)' : 'Sıfır (Kapalı Kutu)' }}
                                    </span>
                                </td>
                            @endforeach
                            @for($i = count($products); $i < 4; $i++)
                                <td class="p-4 border-r border-gray-800/60 last:border-0 bg-[#0f121a]/30"></td>
                            @endfor
                        </tr>

                        {{-- Dinamik Ayırt Edici Teknik Parametreler --}}
                        @php
                            $distinctiveKeys = [
                                'Soket / Platform' => ['icon' => 'fa-microchip', 'color' => 'text-yellow-400'],
                                'İşlemci (CPU)' => ['icon' => 'fa-processor', 'color' => 'text-indigo-400'],
                                'Ekran Kartı (GPU)' => ['icon' => 'fa-tv', 'color' => 'text-purple-400'],
                                'RAM Bellek Mimarisi' => ['icon' => 'fa-memory', 'color' => 'text-cyan-400'],
                                'TDP / Güç Tüketimi' => ['icon' => 'fa-bolt', 'color' => 'text-amber-400'],
                                'Depolama & Hız' => ['icon' => 'fa-hard-drive', 'color' => 'text-emerald-400'],
                                'Maks GPU Uzunluk Desteği' => ['icon' => 'fa-ruler-combined', 'color' => 'text-rose-400'],
                            ];
                        @endphp

                        @foreach($distinctiveKeys as $specLabel => $meta)
                            <tr class="hover:bg-gray-800/30 transition-colors">
                                <td class="p-4 font-bold text-gray-300 bg-[#181c24]/50 border-r border-gray-800 flex items-center gap-2">
                                    <i class="fa-solid {{ $meta['icon'] }} {{ $meta['color'] }}"></i> {{ $specLabel }}
                                </td>
                                @foreach($products as $product)
                                    @php
                                        $dSpecs = $product->distinctive_specs;
                                        $val = $dSpecs[$specLabel] ?? 'N/A';
                                    @endphp
                                    <td class="p-4 text-center border-r border-gray-800/60 last:border-0 font-bold {{ $meta['color'] }}">
                                        @if($val !== 'N/A' && $val !== 'Dahili / Belirtilmedi' && $val !== 'Standart')
                                            <span class="bg-[#181c24] px-3 py-1 rounded-lg border border-gray-700/60 inline-block shadow-sm">
                                                {{ $val }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 font-normal italic text-[11px]">{{ $val }}</span>
                                        @endif
                                    </td>
                                @endforeach
                                @for($i = count($products); $i < 4; $i++)
                                    <td class="p-4 border-r border-gray-800/60 last:border-0 bg-[#0f121a]/30"></td>
                                @endfor
                            </tr>
                        @endforeach

                        {{-- Stok Durumu --}}
                        <tr class="hover:bg-gray-800/30 transition-colors">
                            <td class="p-4 font-bold text-gray-400 bg-[#181c24]/50 border-r border-gray-800 flex items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-yellow-500/80"></i> Stok Durumu
                            </td>
                            @foreach($products as $product)
                                <td class="p-4 text-center border-r border-gray-800/60 last:border-0 font-bold">
                                    @if($product->stock > 0)
                                        <span class="text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/30 text-[11px]">
                                            <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i> Stokta Var ({{ $product->stock }} Adet)
                                        </span>
                                    @else
                                        <span class="text-red-400 bg-red-500/10 px-2.5 py-1 rounded-full border border-red-500/30 text-[11px]">
                                            <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i> Stokta Yok
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                            @for($i = count($products); $i < 4; $i++)
                                <td class="p-4 border-r border-gray-800/60 last:border-0 bg-[#0f121a]/30"></td>
                            @endfor
                        </tr>

                    </tbody>
                </table>
            </div>
        @else
            {{-- Empty Comparison State --}}
            <div class="bg-[#12151c] rounded-3xl border border-gray-800 p-16 text-center shadow-2xl max-w-2xl mx-auto my-12">
                <div class="w-20 h-20 bg-amber-500/10 text-yellow-500 border border-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-lg">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 font-heading">Karşılaştırma Listeniz Boş</h3>
                <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto leading-relaxed">
                    Aynı kategorideki donanımların teknik özelliklerini ve fiyatlarını yan yana incelemek için ürün kartlarındaki karşılaştırma ikonuna tıklayabilirsiniz.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-gradient-btn text-afiDark font-black py-3.5 px-8 rounded-xl transition shadow-lg shadow-yellow-500/20">
                    <i class="fa-solid fa-store text-sm"></i> Kataloğu İncele ve Ürün Ekle
                </a>
            </div>
        @endif

    </div>
</div>

<script>
async function removeFromCompare(productId) {
    try {
        const response = await fetch(`/karsilastir/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (response.ok) {
            window.location.reload();
        }
    } catch (err) {
        console.error('Karşılaştırma silme hatası:', err);
    }
}

async function clearCompareList() {
    if (!confirm('Karşılaştırma listenizdeki tüm ürünleri temizlemek istediğinize emin misiniz?')) return;
    try {
        const response = await fetch(`/karsilastir/clear`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        if (response.ok) {
            window.location.reload();
        }
    } catch (err) {
        console.error('Karşılaştırma temizleme hatası:', err);
    }
}
</script>
@endsection
