@extends('admin.layouts.app')

@section('title', 'Ürün Yönetimi | Afi Bilişim Admin')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white mb-1 flex items-center gap-2">
            <i class="fa-solid fa-boxes-stacked text-adminYellow"></i> Ürün Yönetimi
        </h1>
        <p class="text-slate-400 text-sm">Sistemdeki tüm ürünleri, stok durumlarını ve fiyatları pratikçe filtreleyin.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <button type="button" onclick="openDiscountWizard()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2 text-xs whitespace-nowrap">
            <i class="fa-solid fa-wand-magic-sparkles"></i> İndirim Sihirbazı
        </button>

        <a href="{{ route('admin.products.create') }}" class="bg-adminYellow hover:bg-yellow-400 text-slate-900 font-bold py-2.5 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20 flex items-center gap-2 text-xs whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Yeni Ürün Ekle
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-500 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm">
    <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
</div>
@endif

<!-- Gelişmiş Arama ve Filtreleme Paneli -->
<div class="bg-adminCard rounded-2xl border border-adminBorder p-4 mb-6 shadow-md">
    <form action="{{ route('admin.products.index') }}" method="GET" id="admin-product-filter-form" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 items-end">

        {{-- Canlı Kelime Araması --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Arama (Canlı)</label>
            <div class="relative">
                <input type="text" 
                       name="search" 
                       id="admin-search-input"
                       value="{{ request('search') }}" 
                       oninput="toggleSearchClearBtn(this)"
                       placeholder="Ürün adı, seri no, model, ID..." 
                       class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl pl-9 pr-9 py-2 text-xs text-white placeholder-slate-500 focus:outline-none transition-colors">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                <button type="button" 
                        id="clear-search-btn"
                        onclick="clearAdminSearch()"
                        title="Aramayı Temizle ve Tümünü Listele"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white bg-slate-800 hover:bg-rose-500 w-5 h-5 rounded-full flex items-center justify-center transition-all text-[10px] cursor-pointer {{ request('search') ? '' : 'hidden' }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- Kategori Filtresi --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
            <select name="category_id" 
                    onchange="document.getElementById('admin-product-filter-form').submit()" 
                    class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl px-3 py-2 text-xs text-white focus:outline-none transition-colors">
                <option value="">Tüm Kategoriler</option>
                @foreach($categories as $mainCategory)
                    <option value="{{ $mainCategory->id }}" {{ request('category_id') == $mainCategory->id ? 'selected' : '' }}>
                        📁 {{ $mainCategory->name }}
                    </option>
                    @foreach($mainCategory->children as $subCategory)
                        <option value="{{ $subCategory->id }}" {{ request('category_id') == $subCategory->id ? 'selected' : '' }}>
                            └─ {{ $subCategory->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        {{-- Marka Filtresi --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Marka</label>
            <select name="brand" 
                    onchange="document.getElementById('admin-product-filter-form').submit()" 
                    class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl px-3 py-2 text-xs text-white focus:outline-none transition-colors">
                <option value="">Tüm Markalar</option>
                @if(isset($brands))
                    @foreach($brands as $b)
                        <option value="{{ $b->name }}" {{ request('brand') == $b->name ? 'selected' : '' }}>
                            🏷️ {{ $b->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Ürün Durumu (Sıfır / İkinci El) --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ürün Tipi</label>
            <select name="condition_type" 
                    onchange="document.getElementById('admin-product-filter-form').submit()" 
                    class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl px-3 py-2 text-xs text-white focus:outline-none transition-colors">
                <option value="">Tüm Tipler (Sıfır & İkinci El)</option>
                <option value="new" {{ request('condition_type') == 'new' ? 'selected' : '' }}>✨ Sıfır (Yeni)</option>
                <option value="used" {{ request('condition_type') == 'used' ? 'selected' : '' }}>♻️ İkinci El (Used)</option>
            </select>
        </div>

        {{-- Stok Durumu --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Durumu</label>
            <select name="stock_status" 
                    onchange="document.getElementById('admin-product-filter-form').submit()" 
                    class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl px-3 py-2 text-xs text-white focus:outline-none transition-colors">
                <option value="">Tüm Stok Seviyeleri</option>
                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>🟢 Stokta Var</option>
                <option value="critical" {{ request('stock_status') == 'critical' ? 'selected' : '' }}>🟡 Kritik Stok (≤ 2)</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>🟠 Düşük Stok (≤ 5)</option>
                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>🔴 Tükenenler (0)</option>
            </select>
        </div>

        {{-- Sıralama --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sıralama</label>
            <div class="flex items-center gap-1.5">
                <select name="sort" 
                        onchange="document.getElementById('admin-product-filter-form').submit()" 
                        class="w-full bg-slate-900 border border-slate-700 focus:border-adminYellow rounded-xl px-3 py-2 text-xs text-white focus:outline-none transition-colors">
                    <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>⏱️ En Yeni Eklene Göre</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⌛ En Eski Eklene Göre</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>💵 Fiyat: Düşükten Yükseğe</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>💰 Fiyat: Yüksekten Düşüğe</option>
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>📊 Stok: Azdan Çoğa</option>
                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>📈 Stok: Çoktan Aza</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>🔤 Ürün Adı: A-Z</option>
                </select>

                @if(request()->hasAny(['search', 'category_id', 'condition_type', 'stock_status', 'sort']))
                    <a href="{{ route('admin.products.index') }}" 
                       title="Filtreleri Sıfırla" 
                       class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </div>
        </div>

    </form>
</div>

<!-- Stok Filtre Sekmeleri (Quick Stock Filter Tabs) -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <!-- Tüm Ürünler -->
    <a href="{{ route('admin.products.index', request()->except('stock_status')) }}" 
       class="bg-adminCard p-4 rounded-2xl border transition-all shadow-sm hover:border-slate-500 flex items-center justify-between {{ !request('stock_status') ? 'border-adminYellow ring-1 ring-adminYellow' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tüm Ürünler</p>
            <p class="text-xl font-black text-white">{{ $stockStats['total'] }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400">
            <i class="fa-solid fa-boxes-packing text-base"></i>
        </div>
    </a>

    <!-- Kritik Stok (<= 2) -->
    <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'critical'])) }}" 
       class="bg-adminCard p-4 rounded-2xl border transition-all shadow-sm hover:border-amber-500 flex items-center justify-between {{ request('stock_status') == 'critical' ? 'border-amber-500 ring-1 ring-amber-500 bg-amber-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-triangle-exclamation"></i> Kritik Stok (≤ 2)
            </p>
            <p class="text-xl font-black text-amber-400">{{ $stockStats['critical'] }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
            <i class="fa-solid fa-battery-quarter text-base"></i>
        </div>
    </a>

    <!-- Tükenenler (0 Stok) -->
    <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'out_of_stock'])) }}" 
       class="bg-adminCard p-4 rounded-2xl border transition-all shadow-sm hover:border-rose-500 flex items-center justify-between {{ request('stock_status') == 'out_of_stock' ? 'border-rose-500 ring-1 ring-rose-500 bg-rose-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-rose-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-ban"></i> Tükenenler (0)
            </p>
            <p class="text-xl font-black text-rose-400">{{ $stockStats['out_of_stock'] }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
            <i class="fa-solid fa-circle-xmark text-base"></i>
        </div>
    </a>

    <!-- Stokta Var (> 0) -->
    <a href="{{ route('admin.products.index', array_merge(request()->except('stock_status'), ['stock_status' => 'in_stock'])) }}" 
       class="bg-adminCard p-4 rounded-2xl border transition-all shadow-sm hover:border-emerald-500 flex items-center justify-between {{ request('stock_status') == 'in_stock' ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-500/5' : 'border-adminBorder' }}">
        <div>
            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> Stokta Var
            </p>
            <p class="text-xl font-black text-emerald-400">{{ $stockStats['in_stock'] }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
            <i class="fa-solid fa-battery-full text-base"></i>
        </div>
    </a>
</div>

<!-- Table Card -->
<div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-adminBorder">
                <tr>
                    <th class="px-6 py-4 w-12 text-center">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 rounded bg-slate-700 border-slate-600 text-adminYellow focus:ring-adminYellow">
                    </th>
                    <th class="px-6 py-4">Görsel</th>
                    <th class="px-6 py-4">Ürün Adı</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Fiyat</th>
                    <th class="px-6 py-4">Durum</th>
                    <th class="px-6 py-4">Stok Seviyesi</th>
                    <th class="px-6 py-4 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-adminBorder">
                @forelse($products as $product)
                <tr class="hover:bg-slate-800/40 transition-colors {{ $product->stock == 0 ? 'bg-rose-950/10' : ($product->stock <= 2 ? 'bg-amber-950/10' : '') }}">
                    <td class="px-6 py-4 text-center">
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox w-4 h-4 rounded bg-slate-700 border-slate-600 text-adminYellow focus:ring-adminYellow">
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-14 h-14 rounded-xl bg-slate-800 border border-slate-700 p-1 flex items-center justify-center overflow-hidden">
                            @if($product->main_image)
                                @php
                                    $imgUrl = \Illuminate\Support\Str::startsWith($product->main_image, ['http']) 
                                        ? $product->main_image 
                                        : asset($product->main_image);
                                @endphp
                                <img src="{{ $imgUrl }}" class="w-full h-full object-contain" alt="{{ $product->title }}">
                            @else
                                <div class="text-slate-500 text-xs">
                                    <i class="fa-solid fa-image text-lg"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-white leading-snug">{{ $product->title }}</p>
                        @if($product->serial_number)
                            <div class="mt-1">
                                <span class="bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-mono font-bold px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                    <i class="fa-solid fa-barcode text-[9px]"></i> {{ $product->serial_number }}
                                </span>
                            </div>
                        @endif
                        @if($product->is_discount_active)
                            <div class="mt-1">
                                <span class="bg-red-500/20 text-red-400 border border-red-500/40 text-[10px] font-black px-2.5 py-0.5 rounded-full inline-flex items-center gap-1">
                                    <i class="fa-solid fa-bolt text-amber-400 animate-pulse"></i> Flash İndirim: {{ number_format($product->discount_price, 2, ',', '.') }} ₺
                                </span>
                            </div>
                        @elseif(!empty($product->badge_style))
                            <div class="mt-1">
                                {!! $product->badge_style['badge_html'] !!}
                            </div>
                        @endif

                        <!-- Vitrin Etiketleri (Çok Satan & Haftanın Fırsatı) -->
                        <div class="mt-1.5 flex flex-wrap gap-1">
                            <button type="button" onclick="toggleProductFlag({{ $product->id }}, 'is_bestseller', this)" 
                                    class="px-2 py-0.5 rounded text-[10px] font-bold border transition flex items-center gap-1 {{ $product->is_bestseller ? 'bg-amber-500/20 text-amber-300 border-amber-500/50' : 'bg-slate-800 text-slate-500 border-slate-700 hover:text-slate-300' }}"
                                    title="Tıkla: Çok Satanlar vitrin durumunu değiştir">
                                <i class="fa-solid fa-crown text-[9px] {{ $product->is_bestseller ? 'text-amber-400' : '' }}"></i> Çok Satan
                            </button>
                            <button type="button" onclick="toggleProductFlag({{ $product->id }}, 'is_featured', this)" 
                                    class="px-2 py-0.5 rounded text-[10px] font-bold border transition flex items-center gap-1 {{ $product->is_featured ? 'bg-purple-500/20 text-purple-300 border-purple-500/50' : 'bg-slate-800 text-slate-500 border-slate-700 hover:text-slate-300' }}"
                                    title="Tıkla: Haftanın Fırsatı vitrin durumunu değiştir">
                                <i class="fa-solid fa-star text-[9px] {{ $product->is_featured ? 'text-purple-400' : '' }}"></i> Haftanın Fırsatı
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 font-mono">ID: #{{ $product->id }}</p>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-300">
                        {{ $product->category ? $product->category->name : 'Kategorisiz' }}
                    </td>
                    <td class="px-6 py-4 font-bold text-white text-base">
                        @if($product->is_discount_active)
                            <div>
                                <span class="text-amber-400 font-black">{{ number_format($product->discount_price, 2, ',', '.') }} ₺</span>
                                <span class="block text-xs text-slate-500 line-through font-normal">{{ number_format($product->price, 2, ',', '.') }} ₺</span>
                            </div>
                        @else
                            {{ number_format($product->price, 2, ',', '.') }} ₺
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->condition_type == 'new')
                            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2.5 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-sparkles text-[10px]"></i> Sıfır
                            </span>
                        @else
                            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2.5 py-1 rounded-md text-xs font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-recycle text-[10px]"></i> İkinci El
                            </span>
                        @endif
                    </td>

                    <!-- Stok Gösterge Rozeti (Kritik Stok Uyarılı) -->
                    <td class="px-6 py-4">
                        @if($product->stock == 0)
                            <span class="bg-rose-500/20 text-rose-400 border border-rose-500/40 px-3 py-1.5 rounded-xl text-xs font-black inline-flex items-center gap-1.5 animate-pulse shadow-sm">
                                <i class="fa-solid fa-circle-xmark"></i> Stok Yok (0)
                            </span>
                        @elseif($product->stock <= 2)
                            <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 px-3 py-1.5 rounded-xl text-xs font-black inline-flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation text-amber-300"></i> Kritik ({{ $product->stock }} Adet)
                            </span>
                        @elseif($product->stock <= 5)
                            <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/30 px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-battery-half"></i> Azalıyor ({{ $product->stock }})
                            </span>
                        @else
                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> {{ $product->stock }} Adet
                            </span>
                        @endif
                    </td>

                    <!-- Action Buttons -->
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            @if($product->discount_price || $product->discount_end_date)
                            <button type="button" onclick="removeFlashDiscount({{ $product->id }}, this)" class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500 hover:text-slate-950 flex items-center justify-center transition-colors" title="Flash İndirimi Kaldır / Pasife Al">
                                <i class="fa-solid fa-bolt text-xs"></i>
                            </button>
                            @endif
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-colors"
                               title="Ürünü Düzenle / Stok Güncelle">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-slate-500">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-slate-600"></i>
                        @if(request()->has('search') && request()->search != '')
                            <p class="font-bold text-slate-400">Aradığınız kriterde ürün bulunamadı.</p>
                        @elseif(request('stock_status') == 'critical')
                            <p class="font-bold text-amber-400">Kritik stok seviyesinde (≤ 2) ürün bulunmuyor.</p>
                        @elseif(request('stock_status') == 'out_of_stock')
                            <p class="font-bold text-rose-400">Tükenmiş (0 stoklu) ürün bulunmuyor.</p>
                        @else
                            <p class="font-bold text-slate-400">Henüz hiç ürün eklenmemiş.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="p-4 border-t border-adminBorder">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Hidden Bulk Discount Form -->
<form id="bulk-discount-form" action="{{ route('admin.bulk-discount') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="target_type" id="wizard_target_type" value="selected">
    <input type="hidden" name="category_id" id="wizard_category_id" value="">
    <input type="hidden" name="discount_type" id="wizard_discount_type" value="percentage">
    <input type="hidden" name="discount_value" id="wizard_discount_value" value="">
    <input type="hidden" name="discount_end_date" id="wizard_discount_end_date" value="">
    <div id="dynamic-product-inputs"></div>
</form>

<!-- Bulk Discount Wizard Modal -->
<div id="discountModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-adminCard border border-adminBorder rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 transition-transform duration-300 overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 flex justify-between items-center text-white">
            <h2 class="text-xl font-bold flex items-center gap-3">
                <i class="fa-solid fa-wand-magic-sparkles text-2xl"></i> Toplu İndirim Sihirbazı
            </h2>
            <button onclick="closeDiscountWizard()" class="text-white/70 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="p-6">
            <div class="flex bg-slate-800 rounded-lg p-1 mb-6">
                <button type="button" id="tab-btn-selected" onclick="switchWizardTab('selected')" class="flex-1 py-2 text-sm font-bold rounded-md bg-blue-600 text-white transition-colors">
                    Seçili Ürünler (<span id="selected-count-label">0</span>)
                </button>
                <button type="button" id="tab-btn-category" onclick="switchWizardTab('category')" class="flex-1 py-2 text-sm font-bold rounded-md text-slate-400 hover:text-white transition-colors">
                    Kategori Bazlı İndirim
                </button>
            </div>

            <div id="tab-content-category" class="hidden mb-6">
                <label class="block text-sm font-bold text-slate-300 mb-2">Uygulanacak Kategori</label>
                <select id="modal_category_id" class="w-full bg-slate-800 border border-adminBorder rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                    <option value="">-- Kategori Seçiniz --</option>
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">İndirim Tipi</label>
                    <select id="modal_discount_type" class="w-full bg-slate-800 border border-adminBorder rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                        <option value="percentage">Yüzde İndirimi (%)</option>
                        <option value="fixed">Tutar İndirimi (₺)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Değer</label>
                    <input type="number" id="modal_discount_value" min="0" step="any" placeholder="Örn: 15" class="w-full bg-slate-800 border border-adminBorder rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Bitiş Tarihi</label>
                    <input type="datetime-local" id="modal_discount_end_date" class="w-full bg-slate-800 border border-adminBorder rounded-lg px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDiscountWizard()" class="px-5 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">İptal</button>
                <button type="button" onclick="submitDiscountWizard()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2.5 rounded-lg transition-colors shadow-lg shadow-blue-500/20 flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> İndirimi Uygula
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSearchClearBtn(input) {
        const clearBtn = document.getElementById('clear-search-btn');
        if (clearBtn) {
            if (input.value.trim().length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }
    }

    function clearAdminSearch() {
        const input = document.getElementById('admin-search-input');
        const clearBtn = document.getElementById('clear-search-btn');
        if (input) {
            input.value = '';
            if (clearBtn) clearBtn.classList.add('hidden');
            document.getElementById('admin-product-filter-form').submit();
        }
    }

    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.product-checkbox').forEach(box => {
            box.checked = isChecked;
        });
        updateSelectedCount();
    });

    document.querySelectorAll('.product-checkbox').forEach(box => {
        box.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const count = document.querySelectorAll('.product-checkbox:checked').length;
        document.getElementById('selected-count-label').innerText = count;
    }

    let currentTargetType = 'selected';

    function switchWizardTab(type) {
        currentTargetType = type;
        document.getElementById('wizard_target_type').value = type;
        
        const btnSelected = document.getElementById('tab-btn-selected');
        const btnCategory = document.getElementById('tab-btn-category');
        const contentCategory = document.getElementById('tab-content-category');

        if (type === 'selected') {
            btnSelected.className = 'flex-1 py-2 text-sm font-bold rounded-md bg-blue-600 text-white transition-colors';
            btnCategory.className = 'flex-1 py-2 text-sm font-bold rounded-md text-slate-400 hover:text-white transition-colors';
            contentCategory.classList.add('hidden');
        } else {
            btnCategory.className = 'flex-1 py-2 text-sm font-bold rounded-md bg-blue-600 text-white transition-colors';
            btnSelected.className = 'flex-1 py-2 text-sm font-bold rounded-md text-slate-400 hover:text-white transition-colors';
            contentCategory.classList.remove('hidden');
        }
    }

    function openDiscountWizard() {
        updateSelectedCount();
        const modal = document.getElementById('discountModal');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }

    function closeDiscountWizard() {
        const modal = document.getElementById('discountModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitDiscountWizard() {
        const val = document.getElementById('modal_discount_value').value;
        if(!val || val <= 0) {
            alert("Lütfen geçerli bir indirim değeri girin.");
            return;
        }

        if (currentTargetType === 'selected') {
            if (document.querySelectorAll('.product-checkbox:checked').length === 0) {
                alert("Lütfen en az bir ürün seçin veya kategori bazlı indirimi kullanın.");
                return;
            }
        } else {
            const catId = document.getElementById('modal_category_id').value;
            if(!catId) {
                alert("Lütfen bir kategori seçin.");
                return;
            }
            document.getElementById('wizard_category_id').value = catId;
        }

        if(confirm("İndirim uygulanacaktır. Onaylıyor musunuz?")) {
            document.getElementById('wizard_discount_type').value = document.getElementById('modal_discount_type').value;
            document.getElementById('wizard_discount_value').value = val;
            document.getElementById('wizard_discount_end_date').value = document.getElementById('modal_discount_end_date').value;
            
            const container = document.getElementById('dynamic-product-inputs');
            container.innerHTML = '';
            document.querySelectorAll('.product-checkbox:checked').forEach(box => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = box.value;
                container.appendChild(input);
            });
            
            document.getElementById('bulk-discount-form').submit();
        }
    }

    async function removeFlashDiscount(productId, btnElement) {
        if (window.AfiLoader && btnElement) {
            AfiLoader.btn(btnElement, '');
        }

        try {
            const response = await fetch(`/admin/products/${productId}/remove-discount`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok && data.success) {
                window.location.reload();
            } else {
                if (window.AfiLoader && btnElement) AfiLoader.btnReset(btnElement);
                alert((data && data.message) ? data.message : 'İndirim kaldırılırken bir hata oluştu.');
            }
        } catch (err) {
            console.error('Flash indirim kaldırma hatası:', err);
            if (window.AfiLoader && btnElement) AfiLoader.btnReset(btnElement);
            alert('Sunucu ile iletişim kurulurken bir hata oluştu.');
        }
    }

    async function toggleProductFlag(productId, flag, btnElement) {
        try {
            const response = await fetch(`/admin/products/${productId}/toggle-flag`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ flag: flag })
            });

            const data = await response.json();
            if (response.ok && data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Vitrin durumu güncellenirken bir hata oluştu.');
            }
        } catch (err) {
            console.error('Vitrin etiketi güncelleme hatası:', err);
            alert('Sunucu ile iletişim kurulurken hata oluştu.');
        }
    }
</script>
@endsection
