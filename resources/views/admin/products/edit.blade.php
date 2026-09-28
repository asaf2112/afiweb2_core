@extends('admin.layouts.app')

@section('title', 'Ürünü Düzenle | Afi Bilişim Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-white mb-1">Ürünü Düzenle: {{ $product->title }}</h1>
        <p class="text-slate-400 text-sm">Ürün bilgilerini güncelleyebilirsiniz.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Geri Dön
    </a>
</div>

@if($errors->any())
<div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-adminCard rounded-2xl border border-adminBorder p-6 lg:p-8 shadow-lg">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Sol Sütun -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-300 mb-2">Ürün Adı</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $product->title) }}" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                </div>
                <div>
                    <label for="serial_number" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center justify-between">
                        <span><i class="fa-solid fa-barcode text-amber-400 mr-1"></i> Seri Numarası (SKU)</span>
                        <span class="text-[10px] text-slate-500 font-normal">(Benzersiz)</span>
                    </label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $product->serial_number) }}" class="w-full bg-slate-900 border border-slate-700 text-amber-400 font-mono rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors" placeholder="Örn: AFI-SRN-00001">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="unique_category_select" class="block text-sm font-semibold text-slate-300 mb-2">Kategori</label>
                    <select name="category_id" id="unique_category_select" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors z-50 relative">
                        <option value="">Seçiniz...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $mainCategoryId) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Alt Kategori Seçimi -->
                <div id="sub-category-wrapper" style="display:none;">
                    <label for="sub-category-dropdown" class="block text-sm font-semibold text-slate-300 mb-2">Alt Kategori</label>
                    <select id="sub-category-dropdown" name="sub_category_id" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors relative z-40">
                    </select>
                </div>
                
                <div>
                    <label for="condition_type" class="block text-sm font-semibold text-slate-300 mb-2">Durum</label>
                    <select name="condition_type" id="condition_type" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        <option value="new" {{ (old('condition_type', $product->condition_type) == 'new') ? 'selected' : '' }}>Sıfır</option>
                        <option value="used" {{ (old('condition_type', $product->condition_type) == 'used') ? 'selected' : '' }}>İkinci El</option>
                    </select>
                </div>
            </div>

            <!-- Marka Seçimi -->
            <div>
                <label for="brand" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center justify-between">
                    <span><i class="fa-solid fa-award text-adminYellow mr-1"></i> Ürün Markası</span>
                    <a href="{{ route('admin.brands.index') }}" target="_blank" class="text-[11px] text-yellow-400 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-gear"></i> Markaları Yönet
                    </a>
                </label>
                <div class="relative">
                    <input list="brands_datalist" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" placeholder="Marka seçin veya yeni marka yazın (HP, TwinMOS, ASUS...)" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                    <datalist id="brands_datalist">
                        @if(isset($brands) && $brands->count() > 0)
                            @foreach($brands as $b)
                                <option value="{{ $b->name }}"></option>
                            @endforeach
                        @endif
                    </datalist>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Önerilen markalardan seçebilir veya doğrudan yeni bir marka adı yazabilirsiniz.</p>
            </div>

            <!-- Rozet / Etiket Seçimi -->
            <div>
                <label for="badge_preset_select" class="block text-sm font-semibold text-slate-300 mb-2">
                    <i class="fa-solid fa-certificate text-adminYellow mr-1"></i> Rozet / Etiket Seçimi
                </label>
                
                @php
                    $currentBadge = old('badge', $product->badge ?? '');
                    $presets = ['Oyuncu Kasası (Gaming PC)', 'F/P Canavarı', 'Yayıncı Özel', '2K Gaming İdeal', 'Tükeniyor', 'Editörün Seçimi'];
                    $isCustom = !empty($currentBadge) && !in_array($currentBadge, $presets);
                @endphp

                <select id="badge_preset_select" onchange="toggleCustomBadgeInput(this)" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors mb-3">
                    <option value="" {{ empty($currentBadge) ? 'selected' : '' }}>— Rozet Yok —</option>
                    <option value="Oyuncu Kasası (Gaming PC)" {{ $currentBadge === 'Oyuncu Kasası (Gaming PC)' ? 'selected' : '' }}>🎮 Oyuncu Kasası (Gaming PC)</option>
                    <option value="F/P Canavarı" {{ $currentBadge === 'F/P Canavarı' ? 'selected' : '' }}>⚡ F/P Canavarı</option>
                    <option value="Yayıncı Özel" {{ $currentBadge === 'Yayıncı Özel' ? 'selected' : '' }}>🎧 Yayıncı Özel</option>
                    <option value="2K Gaming İdeal" {{ $currentBadge === '2K Gaming İdeal' ? 'selected' : '' }}>🎮 2K Gaming İdeal</option>
                    <option value="Tükeniyor" {{ $currentBadge === 'Tükeniyor' ? 'selected' : '' }}>🔥 Tükeniyor</option>
                    <option value="Editörün Seçimi" {{ $currentBadge === 'Editörün Seçimi' ? 'selected' : '' }}>⭐ Editörün Seçimi</option>
                    <option value="custom" {{ $isCustom ? 'selected' : '' }}>✏️ Özel Seçim / Kendi Rozetini Yaz...</option>
                </select>

                <!-- Dinamik Özel Metin Input'u -->
                <input type="text" 
                       name="badge" 
                       id="badge_final_input" 
                       value="{{ $currentBadge }}" 
                       class="w-full bg-slate-900 border border-amber-500/50 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-all {{ $isCustom ? '' : 'hidden' }}" 
                       placeholder="Özel rozet metninizi giriniz (Örn: E-Sporcu Seçimi)...">
            </div>

            <!-- Vitrin Etiketleri (Çok Satan & Haftanın Fırsatı) -->
            <div class="bg-slate-900 border border-slate-700 rounded-xl p-4">
                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-400"></i> Anasayfa Vitrin Etiketleri
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/80 border border-slate-700 hover:border-amber-500/50 cursor-pointer transition">
                        <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-900 border-slate-600 text-amber-400 focus:ring-amber-400">
                        <div>
                            <span class="text-xs font-bold text-white block">👑 Çok Satan</span>
                            <span class="text-[10px] text-slate-400">"Çok Satanlar" vitrininde göster</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/80 border border-slate-700 hover:border-purple-500/50 cursor-pointer transition">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-900 border-slate-600 text-purple-400 focus:ring-purple-400">
                        <div>
                            <span class="text-xs font-bold text-white block">⚡ Haftanın Fırsatı</span>
                            <span class="text-[10px] text-slate-400">"Haftanın Ürünleri" vitrininde göster</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- İkinci El Detayları: Sadece "İkinci El" seçilince görünür --}}
            <div id="usage-status-wrapper"
                 style="{{ old('condition_type', $product->condition_type) === 'used' ? '' : 'display:none;' }}">
                <div class="bg-emerald-900/20 border border-emerald-500/30 rounded-xl p-4">
                    <h4 class="text-emerald-400 font-bold text-sm mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-recycle"></i> İkinci El Detayları
                    </h4>
                    <label for="usage_status" class="block text-sm font-semibold text-slate-300 mb-2">
                        Masaüstü Bilgisayar Tipi
                        <span class="text-slate-500 font-normal">(Masaüstü ise seçiniz)</span>
                    </label>
                    <select name="usage_status" id="usage_status" class="w-full bg-slate-900 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                        <option value="">— Laptop / Diğer (tip yok) —</option>
                        <option value="Full Set" {{ old('usage_status', $product->usage_status) == 'Full Set' ? 'selected' : '' }}>📦 Full Set (Monitör + Klavye/Mouse dahil)</option>
                        <option value="Sadece Kasa" {{ old('usage_status', $product->usage_status) == 'Sadece Kasa' ? 'selected' : '' }}>🖥️ Sadece Kasa</option>
                        <option value="Sadece Monitör" {{ old('usage_status', $product->usage_status) == 'Sadece Monitör' ? 'selected' : '' }}>🖵 Sadece Monitör</option>
                    </select>
                    <p class="text-slate-500 text-xs mt-1.5">Masaüstü bilgisayar satıyorsanız tip seçin. Laptop için boş bırakın.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-semibold text-slate-300 mb-2">Normal Fiyat (₺)</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                </div>
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-300 mb-2">Stok Miktarı</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                </div>
            </div>

            <!-- Flash İndirim & Kampanya Alanı -->
            <div class="bg-gradient-to-br from-red-950/40 via-slate-900 to-amber-950/30 border border-red-500/30 rounded-xl p-4 sm:p-5 mt-4 shadow-md">
                <div class="flex items-center justify-between mb-3 border-b border-red-500/20 pb-2">
                    <h4 class="text-red-400 font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-400 animate-pulse"></i> Flash İndirim & Kampanya Tanımla
                    </h4>
                    <span class="text-[11px] bg-red-500/20 text-red-300 border border-red-500/30 px-2 py-0.5 rounded-full font-semibold">Özel Fırsat Vitrini</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="discount_price" class="block text-xs font-semibold text-slate-300 mb-1.5">
                            İndirimli Kampanya Fiyatı (₺)
                        </label>
                        <input type="number" step="0.01" name="discount_price" id="discount_price" value="{{ old('discount_price', $product->discount_price) }}" class="w-full bg-slate-900 border border-red-500/40 text-amber-400 font-bold rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors" placeholder="Örn: 24999.00 (Boş ise indirimsiz)">
                    </div>

                    <div>
                        <label for="discount_end_date" class="block text-xs font-semibold text-slate-300 mb-1.5">
                            İndirim Bitiş Tarihi & Saati
                        </label>
                        <input type="datetime-local" name="discount_end_date" id="discount_end_date" value="{{ old('discount_end_date', $product->discount_end_date ? \Carbon\Carbon::parse($product->discount_end_date)->format('Y-m-d\TH:i') : '') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-colors">
                        <input type="hidden" name="discount_expires_at" id="discount_expires_at" value="{{ old('discount_expires_at', $product->discount_end_date ? \Carbon\Carbon::parse($product->discount_end_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                <!-- Hızlı Süre Seçim Butonları -->
                <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-slate-400 font-medium mr-1 text-[11px]">Hızlı Süre:</span>
                    <button type="button" onclick="setDiscountDuration(1)" class="bg-slate-800 hover:bg-red-900/60 text-slate-300 hover:text-white border border-slate-700 px-2.5 py-1 rounded-lg transition-all font-semibold cursor-pointer">
                        +1 Saat
                    </button>
                    <button type="button" onclick="setDiscountDuration(6)" class="bg-slate-800 hover:bg-red-900/60 text-slate-300 hover:text-white border border-slate-700 px-2.5 py-1 rounded-lg transition-all font-semibold cursor-pointer">
                        +6 Saat
                    </button>
                    <button type="button" onclick="setDiscountDuration(24)" class="bg-slate-800 hover:bg-red-900/60 text-slate-300 hover:text-white border border-slate-700 px-2.5 py-1 rounded-lg transition-all font-semibold cursor-pointer">
                        +1 Gün (24S)
                    </button>
                    <button type="button" onclick="setDiscountDuration(48)" class="bg-slate-800 hover:bg-red-900/60 text-slate-300 hover:text-white border border-slate-700 px-2.5 py-1 rounded-lg transition-all font-semibold cursor-pointer">
                        +2 Gün
                    </button>
                    <button type="button" onclick="setDiscountDuration(168)" class="bg-slate-800 hover:bg-red-900/60 text-slate-300 hover:text-white border border-slate-700 px-2.5 py-1 rounded-lg transition-all font-semibold cursor-pointer">
                        +1 Hafta
                    </button>
                    <button type="button" onclick="clearDiscountDuration()" class="bg-red-950/60 hover:bg-red-800 text-red-300 border border-red-800/60 px-2.5 py-1 rounded-lg transition-all font-semibold ml-auto cursor-pointer">
                        <i class="fa-solid fa-trash-can"></i> İndirimi Temizle
                    </button>
                </div>
            </div>

            <!-- Dinamik Özellikler Alanı -->
            <div id="specs-wrapper">
                <div id="dynamic-specs-container" class="space-y-4"></div>
            </div>
            
        </div>

        <!-- Sağ Sütun -->
        <div class="space-y-6">


            <div>
                <label for="description" class="block text-sm font-semibold text-slate-300 mb-2">Ürün Açıklaması</label>
                <textarea name="description" id="description" rows="5" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 mt-6">
                <h3 class="text-lg font-bold text-white mb-4 border-b border-slate-700 pb-2">Görsel Yönetimi</h3>
                
                <!-- Yeni Görsel Yükleme Alanları -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label for="main_image" class="block text-sm font-semibold text-slate-300 mb-2">Ana Görsel (Değiştir / Yeni Ekle)</label>
                        <div class="w-full bg-slate-800 border-2 border-dashed border-slate-700 rounded-xl p-4 text-center hover:border-adminYellow transition-colors group cursor-pointer relative">
                            <input type="file" name="main_image" id="main_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-500 mb-2 group-hover:text-adminYellow transition-colors"></i>
                            <p class="text-slate-400 text-xs group-hover:text-white transition-colors">Ana görsel seçin veya sürükleyin.</p>
                        </div>
                    </div>
                    <div>
                        <label for="images" class="block text-sm font-semibold text-slate-300 mb-2">Galeriye Yeni Resimler Ekle</label>
                        <div class="w-full bg-slate-800 border-2 border-dashed border-slate-700 rounded-xl p-4 text-center hover:border-adminYellow transition-colors group cursor-pointer relative">
                            <input type="file" name="images[]" id="images" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                            <i class="fa-solid fa-images text-2xl text-slate-500 mb-2 group-hover:text-adminYellow transition-colors"></i>
                            <p class="text-slate-400 text-xs group-hover:text-white transition-colors">Çoklu resim seçin veya sürükleyin.</p>
                        </div>
                    </div>
                </div>

                <!-- Mevcut Görseller Grid -->
                <label class="block text-sm font-semibold text-slate-300 mb-2">Mevcut Görseller</label>
                
                @if($product->main_image || ($product->images && $product->images->count() > 0))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="gallery-grid">
                        <!-- Ana Görsel -->
                        @if($product->main_image)
                            <div class="relative group rounded-lg overflow-hidden border-2 border-adminYellow transition-opacity duration-300" id="main-image-box">
                                <div class="absolute top-0 left-0 bg-adminYellow text-slate-900 text-xs font-bold px-2 py-1 z-10 rounded-br-lg shadow-sm">Ana Görsel</div>
                                <img src="{{ asset($product->main_image) }}" class="w-full h-24 md:h-32 object-cover transition-transform group-hover:scale-105">
                                <button type="button" onclick="deleteMainImage({{ $product->id }})" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all z-20 shadow-lg transform hover:scale-110">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @endif

                        <!-- Galeri Görselleri -->
                        @if($product->images)
                            @foreach($product->images as $image)
                                <div class="relative group rounded-lg overflow-hidden border border-slate-700 transition-opacity duration-300" id="gallery-image-{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-24 md:h-32 object-cover transition-transform group-hover:scale-105">
                                    <button type="button" onclick="deleteGalleryImage({{ $image->id }})" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all z-20 shadow-lg transform hover:scale-110">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 text-center" id="empty-gallery-msg">
                        <i class="fa-regular fa-images text-4xl text-slate-600 mb-3"></i>
                        <p class="text-slate-400">Henüz galeri görseli eklenmemiş.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <div class="mt-8 pt-6 border-t border-adminBorder flex justify-end">
        <button type="submit" id="edit-product-btn" class="bg-adminYellow hover:bg-yellow-400 text-slate-900 font-bold py-3 px-8 rounded-xl shadow-lg shadow-yellow-500/20 transition-all transform hover:-translate-y-1 flex items-center gap-2">
            <i class="fa-solid fa-check"></i> Değişiklikleri Kaydet
        </button>
    </div>

</form>

@push('scripts')
<script>
    const existingSpecs = @json($product->specs ?? []);
    const initialSubCategoryId = "{{ old('sub_category_id', $subCategoryId) }}";
    let isProcessing = false;

    function toggleCustomBadgeInput(selectEl) {
        const finalInput = document.getElementById('badge_final_input');
        if (!finalInput) return;

        if (selectEl.value === 'custom') {
            finalInput.classList.remove('hidden');
            finalInput.focus();
        } else {
            finalInput.classList.add('hidden');
            finalInput.value = selectEl.value;
        }
    }

    async function loadSpecs(categoryId) {
        if (isProcessing) return;
        isProcessing = true;

        const container = document.getElementById('dynamic-specs-container');
        if (!container) return false;

        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }

        if(!categoryId) {
            isProcessing = false;
            return false;
        }

        if (categoryId == 4 || categoryId == 5) {
            let cpuVal = existingSpecs['cpu'] || '';
            let ramVal = existingSpecs['ram'] || '';
            let gpuVal = existingSpecs['gpu'] || '';
            let storageVal = existingSpecs['storage'] || '';
            let screenVal = existingSpecs['screen_size'] || '';

            container.innerHTML = `
                <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 mt-6">
                    <h3 class="text-slate-300 font-bold mb-4 border-b border-slate-700 pb-2">Bilgisayar Özellikleri</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">İşlemci</label>
                            <input type="text" name="specs[cpu]" value="${cpuVal}" placeholder="Örn: Intel i7" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">RAM</label>
                            <input type="text" name="specs[ram]" value="${ramVal}" placeholder="Örn: 16GB DDR4" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Ekran Kartı</label>
                            <input type="text" name="specs[gpu]" value="${gpuVal}" placeholder="Örn: RTX 4060" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Depolama</label>
                            <input type="text" name="specs[storage]" value="${storageVal}" placeholder="Örn: 512GB SSD" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Ekran Boyutu</label>
                            <input type="text" name="specs[screen_size]" value="${screenVal}" placeholder="Örn: 15.6 inç" class="w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors">
                        </div>
                    </div>
                </div>
            `;
            isProcessing = false;
            return false;
        }

        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'text-slate-400 text-sm mt-4';
        loadingDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Özellikler yükleniyor...';
        container.appendChild(loadingDiv);

        try {
            const res = await fetch(`/admin/categories/${categoryId}/specs`);
            const data = await res.json();
            
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            
            if(data && data.length > 0) {
                const fragment = document.createDocumentFragment();
                const wrapperDiv = document.createElement('div');
                wrapperDiv.className = 'bg-slate-800 p-5 rounded-xl border border-slate-700 mt-6';
                const heading = document.createElement('h3');
                heading.className = 'text-slate-300 font-bold mb-4 border-b border-slate-700 pb-2';
                heading.innerText = 'Kategori Özellikleri';
                wrapperDiv.appendChild(heading);
                const fieldsWrapper = document.createElement('div');
                fieldsWrapper.className = 'space-y-4';

                data.forEach(spec => {
                    let val = existingSpecs[spec.name] ? existingSpecs[spec.name] : '';
                    const fieldDiv = document.createElement('div');
                    const label = document.createElement('label');
                    label.className = 'block text-sm font-semibold text-slate-300 mb-2';
                    label.innerText = spec.name;
                    fieldDiv.appendChild(label);
                    const input = document.createElement('input');
                    input.type = spec.type || 'text';
                    input.name = `specs[${spec.name}]`;
                    input.value = val;
                    input.className = 'w-full bg-slate-900 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-adminYellow focus:ring-1 focus:ring-adminYellow transition-colors';
                    input.placeholder = `${spec.name} değerini girin`;
                    fieldDiv.appendChild(input);
                    fieldsWrapper.appendChild(fieldDiv);
                });

                wrapperDiv.appendChild(fieldsWrapper);
                fragment.appendChild(wrapperDiv);
                container.appendChild(fragment);
            } else {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'text-slate-500 text-sm mt-4 italic';
                emptyDiv.innerText = 'Bu kategoriye ait özellik yok.';
                container.appendChild(emptyDiv);
            }
        } catch (err) {
            console.error('Specs fetch error:', err);
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-400 text-sm mt-4';
            errorDiv.innerText = 'Özellikler yüklenirken bir hata oluştu.';
            container.appendChild(errorDiv);
        } finally {
            isProcessing = false;
        }

        return false;
    }

    // İkinci El Detayları: sadece condition_type === 'used' ise göster
    function checkUsageStatusVisibility() {
        const conditionSelect = document.getElementById('condition_type');
        const usageWrapper   = document.getElementById('usage-status-wrapper');
        const usageSelect    = document.getElementById('usage_status');
        if (!conditionSelect || !usageWrapper) return;
        if (conditionSelect.value === 'used') {
            usageWrapper.style.display = 'block';
        } else {
            usageWrapper.style.display = 'none';
            if (usageSelect) usageSelect.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect  = document.getElementById('unique_category_select');
        const subDropdown     = document.getElementById('sub-category-dropdown');
        const subWrapper      = document.getElementById('sub-category-wrapper');
        const conditionSelect = document.getElementById('condition_type');

        // Durum değişince İkinci El alanını göster/gizle
        if (conditionSelect) conditionSelect.addEventListener('change', checkUsageStatusVisibility);
        // Sayfa yüklendiğinde mevcut değeri kontrol et
        checkUsageStatusVisibility();

        function updateSlugFromOption(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            const slug = opt ? (opt.getAttribute('data-slug') || opt.text.toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'')) : '';
            // slug kullanımı artık checkUsageStatusVisibility'de yok — kategoriden bağımsız
        }

        function fetchSubCategories(parentId, selectedSubId = null) {
            if (parentId) {
                fetch(`/api/subcategories/${parentId}`)
                    .then(response => response.json())
                    .then(data => {
                        subDropdown.innerHTML = '<option value="">Alt Kategori Seçiniz...</option>';
                        if(data.length > 0) {
                            subWrapper.style.display = 'block';
                            data.forEach(sub => {
                                let selected = selectedSubId == sub.id ? 'selected' : '';
                                subDropdown.innerHTML += `<option value="${sub.id}" ${selected}>${sub.name}</option>`;
                            });
                        } else {
                            subWrapper.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Alt kategoriler yüklenirken hata oluştu:', error);
                        subWrapper.style.display = 'none';
                    });
            } else {
                subWrapper.style.display = 'none';
                subDropdown.innerHTML = '';
            }
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                let parentId = this.value;
                if (typeof loadSpecs === 'function') loadSpecs(parentId);
                fetchSubCategories(parentId);
            });

            if (categorySelect.value) {
                fetchSubCategories(categorySelect.value, initialSubCategoryId);
                loadSpecs(initialSubCategoryId || categorySelect.value);
            }
        }

        if (subDropdown) {
            subDropdown.addEventListener('change', function() {
                let subId    = this.value;
                let parentId = categorySelect.value;
                if (typeof loadSpecs === 'function') loadSpecs(subId ? subId : parentId);
            });
        }

        /* ── Admin Ürün Düzenleme — Form Submit Loader & AJAX POST/PUT ── */
        const editProductForm = document.querySelector('form[action*="products"]');
        const editProductBtn  = document.getElementById('edit-product-btn');
        if (editProductForm) {
            editProductForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // İndirim süre ve fiyat değerlerini eşitle
                const discEndDate = document.getElementById('discount_end_date');
                const discExpires = document.getElementById('discount_expires_at');
                if (discEndDate && discExpires) {
                    discExpires.value = discEndDate.value;
                }

                if (window.AfiLoader) {
                    AfiLoader.show('Ürün güncelleniyor...');
                    if (editProductBtn) AfiLoader.btn(editProductBtn, 'Kaydediliyor...');
                }

                const formData = new FormData(editProductForm);

                try {
                    const response = await fetch(editProductForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        window.location.href = "{{ route('admin.products.index') }}";
                    } else {
                        if (window.AfiLoader) {
                            AfiLoader.hide();
                            if (editProductBtn) AfiLoader.btnReset(editProductBtn);
                        }
                        let errorMsg = (data && data.message) ? data.message : 'Ürün güncellenirken bir hata oluştu.';
                        if (data && data.errors) {
                            errorMsg = Object.values(data.errors).flat().join('\n');
                        }
                        alert(errorMsg);
                    }
                } catch (err) {
                    console.error('Update submit error:', err);
                    if (window.AfiLoader) {
                        AfiLoader.hide();
                        if (editProductBtn) AfiLoader.btnReset(editProductBtn);
                    }
                    alert('Sunucu ile iletişim kurulurken bir hata oluştu.');
                }
            });
        }
    });

    // --- Görsel Silme AJAX (Fetch API) İşlemleri ---
    function fadeOutAndRemove(element) {
        element.style.opacity = '0';
        setTimeout(() => {
            element.remove();
            checkEmptyGallery();
        }, 300);
    }

    function checkEmptyGallery() {
        const grid = document.getElementById('gallery-grid');
        if (grid && grid.children.length === 0) {
            grid.remove();
            const parent = document.querySelector('.bg-slate-900.mt-6'); // Görsel yönetimi container
            const emptyMsg = document.createElement('div');
            emptyMsg.className = 'bg-slate-800/50 border border-slate-700 rounded-xl p-6 text-center';
            emptyMsg.id = 'empty-gallery-msg';
            emptyMsg.innerHTML = `
                <i class="fa-regular fa-images text-4xl text-slate-600 mb-3"></i>
                <p class="text-slate-400">Henüz galeri görseli eklenmemiş.</p>
            `;
            parent.appendChild(emptyMsg);
        }
    }

    function deleteMainImage(productId) {
        if(!confirm('Ana görseli silmek istediğinize emin misiniz?')) return;

        fetch(`/admin/products/${productId}/main-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const box = document.getElementById('main-image-box');
                if(box) fadeOutAndRemove(box);
            } else {
                alert('Silme işlemi başarısız oldu.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Silme işlemi sırasında bir hata oluştu.');
        });
    }

    function deleteGalleryImage(imageId) {
        if(!confirm('Bu görseli galeriden silmek istediğinize emin misiniz?')) return;

        fetch(`/admin/products/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const box = document.getElementById(`gallery-image-${imageId}`);
                if(box) fadeOutAndRemove(box);
            } else {
                alert('Silme işlemi başarısız oldu.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Silme işlemi sırasında bir hata oluştu.');
        });
    }

    window.setDiscountDuration = function(hours) {
        const dateInput = document.getElementById('discount_end_date');
        if (!dateInput) return;
        const now = new Date();
        now.setHours(now.getHours() + parseInt(hours, 10));
        
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hoursStr = String(now.getHours()).padStart(2, '0');
        const minutesStr = String(now.getMinutes()).padStart(2, '0');
        
        const formatted = `${year}-${month}-${day}T${hoursStr}:${minutesStr}`;
        dateInput.value = formatted;
        dateInput.dispatchEvent(new Event('input', { bubbles: true }));
        dateInput.dispatchEvent(new Event('change', { bubbles: true }));
    };

    window.clearDiscountDuration = function() {
        const pInput = document.getElementById('discount_price');
        const dInput = document.getElementById('discount_end_date');
        if (pInput) {
            pInput.value = '';
            pInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (dInput) {
            dInput.value = '';
            dInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };
</script>
@endpush
@endsection
