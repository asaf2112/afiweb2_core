@extends('layouts.app')

@section('title', 'PC Toplama Sihirbazı | Akıllı Donanım Uyumluluk Matrisi | Afi Bilişim')

@section('content')
<div class="bg-afiDark text-white py-10 min-h-screen relative overflow-hidden">
    
    <!-- Ambient Background Glows -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -left-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 relative z-10">
        
        <!-- Page Header -->
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/30 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-wider inline-flex items-center gap-2 mb-3 shadow-sm">
                <i class="fa-solid fa-microchip"></i> Akıllı Donanım Matrisi
            </span>
            <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl font-black mb-3">
                PC Toplama <span class="text-gradient">Sihirbazı</span>
            </h1>
            <p class="text-gray-400 text-xs sm:text-sm md:text-base font-medium">
                Hayalinizdeki bilgisayarı kolayca toplayın. İşlemci soketi, bellek tipi, kasa boyutu ve güç kaynağı (TDP) uyumu anlık olarak denetlenir.
            </p>
        </div>

        <!-- System Progress & Quick Status Bar (Karmaşıklığı Önleyen Temiz Durum Çubuğu) -->
        <div class="mb-8 bg-gray-900/90 border border-gray-800 p-4 sm:p-5 rounded-3xl backdrop-blur-xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5 w-full md:w-auto">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-yellow-500 to-amber-400 text-afiDark flex items-center justify-center text-xl font-black shadow-lg shadow-yellow-500/20 shrink-0">
                    <i class="fa-solid fa-computer"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-gray-400">Sistem İlerlemesi</span>
                        <span id="progressCountBadge" class="bg-gray-800 text-yellow-400 text-[11px] font-bold px-2 py-0.5 rounded-full border border-gray-700">0 / 8 Parça Seçildi</span>
                    </div>
                    <div class="w-48 sm:w-64 h-2 bg-gray-950 rounded-full mt-1.5 overflow-hidden border border-gray-800">
                        <div id="buildProgressBar" class="h-full bg-gradient-to-r from-yellow-500 to-amber-400 transition-all duration-500 rounded-full" style="width: 0%;"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                <div id="topCompatibilityChip" class="bg-gray-800/80 text-gray-400 border border-gray-700 text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-gray-500"></span> Parça Seçimi Bekleniyor
                </div>
                <button type="button" onclick="resetEntireBuild()" class="text-xs font-bold text-gray-400 hover:text-red-400 px-3 py-1.5 rounded-xl hover:bg-red-500/10 transition-colors flex items-center gap-1.5 cursor-pointer" title="Seçimleri Temizle">
                    <i class="fa-solid fa-arrows-rotate"></i> Sistemi Sıfırla
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Hardware Selection Slots (8 Cols) -->
            <div class="lg:col-span-8 space-y-4">
                
                @php
                    $slots = [
                        ['key' => 'cpu', 'step' => '01', 'name' => 'İşlemci (CPU)', 'slug' => 'islemci', 'icon' => 'fa-microchip', 'accent' => 'from-blue-500/20 to-blue-600/10 text-blue-400 border-blue-500/30', 'required' => true, 'desc' => 'Bilgisayarınızın ana işlem gücü'],
                        ['key' => 'motherboard', 'step' => '02', 'name' => 'Anakart (Motherboard)', 'slug' => 'anakart', 'icon' => 'fa-border-all', 'accent' => 'from-emerald-500/20 to-emerald-600/10 text-emerald-400 border-emerald-500/30', 'required' => true, 'desc' => 'İşlemci soketi ile tam uyumlu model'],
                        ['key' => 'ram', 'step' => '03', 'name' => 'Bellek (RAM)', 'slug' => 'ram', 'icon' => 'fa-memory', 'accent' => 'from-purple-500/20 to-purple-600/10 text-purple-400 border-purple-500/30', 'required' => true, 'desc' => 'DDR4 veya DDR5 bellek modülleri'],
                        ['key' => 'gpu', 'step' => '04', 'name' => 'Ekran Kartı (GPU)', 'slug' => 'ekran-karti', 'icon' => 'fa-tv', 'accent' => 'from-violet-500/20 to-violet-600/10 text-violet-400 border-violet-500/30', 'required' => false, 'desc' => 'Oyun ve grafik performans donanımı'],
                        ['key' => 'psu', 'step' => '05', 'name' => 'Güç Kaynağı (PSU)', 'slug' => 'guc-kaynagi', 'icon' => 'fa-plug', 'accent' => 'from-amber-500/20 to-amber-600/10 text-amber-400 border-amber-500/30', 'required' => true, 'desc' => 'Sistem TDP ihtiyacına uygun watt gücü'],
                        ['key' => 'ssd', 'step' => '06', 'name' => 'SSD Depolama (M.2 / NVMe)', 'slug' => 'ssd', 'icon' => 'fa-bolt-lightning', 'accent' => 'from-cyan-500/20 to-cyan-600/10 text-cyan-400 border-cyan-500/30', 'required' => false, 'desc' => 'Yüksek hızlı işletim sistemi diski'],
                        ['key' => 'hdd', 'step' => '07', 'name' => 'HDD Depolama (Mekanik Disk)', 'slug' => 'hdd', 'icon' => 'fa-hard-drive', 'accent' => 'from-sky-500/20 to-sky-600/10 text-sky-400 border-sky-500/30', 'required' => false, 'desc' => 'Geniş arşiv ve depolama alanı'],
                        ['key' => 'case', 'step' => '08', 'name' => 'Bilgisayar Kasası (Case)', 'slug' => 'kasa', 'icon' => 'fa-computer', 'accent' => 'from-yellow-500/20 to-yellow-600/10 text-yellow-400 border-yellow-500/30', 'required' => false, 'desc' => 'Bileşenlerin yerleşeceği kasa gövdesi'],
                    ];
                @endphp

                @foreach($slots as $slot)
                    <div id="slot-card-{{ $slot['key'] }}" class="bg-gray-900/90 border border-gray-800 rounded-3xl p-4 sm:p-5 hover:border-gray-700/80 transition-all duration-300 shadow-lg flex items-center justify-between gap-3 sm:gap-4 group">
                        
                        <!-- Left Info Column -->
                        <div class="flex items-center gap-3 sm:gap-3.5 min-w-0 flex-1">
                            <!-- Slot Step & Icon -->
                            <div class="relative shrink-0">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-br {{ $slot['accent'] }} border flex items-center justify-center text-lg sm:text-xl shadow-inner">
                                    <i class="fa-solid {{ $slot['icon'] }}"></i>
                                </div>
                                <span class="absolute -top-1.5 -left-1.5 bg-gray-950 text-gray-400 text-[9px] font-black px-1.5 py-0.2 rounded-full border border-gray-800">
                                    {{ $slot['step'] }}
                                </span>
                            </div>

                            <!-- Title & Part Info -->
                            <div class="min-w-0 flex-1 pr-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">
                                        {{ $slot['name'] }}
                                    </span>
                                    @if($slot['required'])
                                        <span class="text-[9px] bg-red-500/15 text-red-400 border border-red-500/30 px-1.5 py-0.2 rounded-full font-black uppercase">Zorunlu</span>
                                    @else
                                        <span class="text-[9px] bg-gray-800 text-gray-400 border border-gray-700 px-1.5 py-0.2 rounded-full font-semibold uppercase">Opsiyonel</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 mt-0.5 min-w-0">
                                    <img id="slot-img-{{ $slot['key'] }}" src="" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg object-contain bg-gray-950 border border-gray-800 hidden shrink-0 p-0.5" alt="">
                                    <h4 id="slot-name-{{ $slot['key'] }}" class="text-xs sm:text-sm md:text-base font-bold text-gray-400 truncate max-w-full">Parça seçilmedi</h4>
                                </div>

                                <div id="slot-meta-{{ $slot['key'] }}" class="text-[10px] sm:text-[11px] text-gray-500 hidden mt-1 flex items-center gap-1.5 flex-wrap"></div>
                            </div>
                        </div>

                        <!-- Right Actions & Quantity -->
                        <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 ml-auto">
                            <!-- Quantity Control UI (Sadece RAM ve SSD/HDD için açılır) -->
                            <div id="slot-qty-container-{{ $slot['key'] }}" class="hidden items-center bg-gray-950 border border-gray-800 rounded-xl p-1 gap-1 shadow-inner">
                                <button type="button" onclick="changeQuantity('{{ $slot['key'] }}', -1)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 font-black flex items-center justify-center text-xs transition cursor-pointer" title="Adet Azalt">-</button>
                                <span id="slot-qty-val-{{ $slot['key'] }}" class="w-6 text-center text-xs font-black text-yellow-400">1</span>
                                <button type="button" onclick="changeQuantity('{{ $slot['key'] }}', 1)" class="w-6 h-6 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 font-black flex items-center justify-center text-xs transition cursor-pointer" title="Adet Artır">+</button>
                            </div>

                            <span id="slot-price-{{ $slot['key'] }}" class="text-xs sm:text-sm md:text-base font-black text-yellow-400 hidden whitespace-nowrap">0,00 TL</span>
                            
                            <button type="button" onclick="openPartModal('{{ $slot['key'] }}', '{{ $slot['name'] }}', '{{ $slot['slug'] }}')" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark font-black px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl sm:rounded-2xl text-xs transition-all shadow-md shadow-yellow-500/10 hover:shadow-yellow-500/25 flex items-center gap-1.5 cursor-pointer shrink-0 whitespace-nowrap">
                                <i id="slot-btn-icon-{{ $slot['key'] }}" class="fa-solid fa-plus text-xs"></i> 
                                <span id="slot-btn-text-{{ $slot['key'] }}">Parça Seç</span>
                            </button>
                            
                            <button type="button" id="slot-remove-{{ $slot['key'] }}" onclick="removePart('{{ $slot['key'] }}')" class="bg-red-500/15 text-red-400 hover:bg-red-500 hover:text-white border border-red-500/30 p-2 sm:px-3 sm:py-2 rounded-xl sm:rounded-2xl text-xs font-bold transition-all hidden cursor-pointer shrink-0 flex items-center gap-1" title="Seçimi Kaldır">
                                <i class="fa-solid fa-trash-can"></i> <span class="hidden sm:inline">Kaldır</span>
                            </button>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Right Column: Live Compatibility Status & Summary (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Status Box -->
                <div class="bg-gray-900/95 border border-gray-800 rounded-3xl p-5 sm:p-6 shadow-2xl backdrop-blur-xl sticky top-24 space-y-5">
                    
                    <!-- Header & Compatibility -->
                    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                        <div>
                            <h3 class="text-lg font-black text-white">Sistem Özeti</h3>
                            <p class="text-[11px] text-gray-400">Canlı uyumluluk denetimi</p>
                        </div>
                        <span id="compatibilityBadge" class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-black px-2.5 py-1 rounded-full uppercase flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-check"></i> Uyumlu
                        </span>
                    </div>

                    <!-- Compatibility Matrix Indicators -->
                    <div class="space-y-3 bg-gray-950/80 p-4 rounded-2xl border border-gray-800 text-xs shadow-inner">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 flex items-center gap-1.5"><i class="fa-solid fa-microchip text-yellow-500/80"></i> İşlemci Soketi:</span>
                            <span id="summaryCpuSocket" class="font-bold text-gray-300">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 flex items-center gap-1.5"><i class="fa-solid fa-memory text-purple-400"></i> Bellek (RAM) Tipi:</span>
                            <span id="summaryRamType" class="font-bold text-gray-300">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 flex items-center gap-1.5"><i class="fa-solid fa-bolt text-amber-400"></i> Tahmini Güç:</span>
                            <span id="summaryTdp" class="font-bold text-yellow-400">0 Watt</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 flex items-center gap-1.5"><i class="fa-solid fa-plug text-emerald-400"></i> Tavsiye Edilen PSU:</span>
                            <span id="summaryMinPsu" class="font-bold text-emerald-400">En az 0W</span>
                        </div>
                    </div>

                    <!-- Selected Parts Visual Showcase -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-list-check text-yellow-500"></i> Seçilen Parçalar</span>
                            <span id="selectedPartsCountBadge" class="bg-gray-800 text-yellow-400 px-2 py-0.5 rounded-full text-[10px] font-black border border-gray-700">0 Parça</span>
                        </div>
                        
                        <div id="summaryPartsShowcase" class="space-y-2 max-h-56 overflow-y-auto pr-1" style="scrollbar-width:thin; scrollbar-color:#374151 transparent;">
                            <div class="text-center py-6 bg-gray-950/50 rounded-2xl border border-dashed border-gray-800 text-gray-500 text-xs">
                                <i class="fa-solid fa-cube text-xl mb-1.5 block text-gray-600"></i>
                                Henüz parça eklenmedi.
                            </div>
                        </div>
                    </div>

                    <!-- Live Validation Error / Warning Boxes -->
                    <div id="validationErrorBox" class="hidden bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-2xl text-xs space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-triangle-exclamation"></i> Uyumsuz Parça Uyarısı!
                        </div>
                        <ul id="validationErrorList" class="list-disc list-inside space-y-1"></ul>
                    </div>

                    <div id="validationWarningBox" class="hidden bg-yellow-500/10 border border-yellow-500/30 text-yellow-300 p-4 rounded-2xl text-xs space-y-2">
                        <div class="font-bold flex items-center gap-1.5 text-sm">
                            <i class="fa-solid fa-circle-info"></i> Tavsiye Uyarısı
                        </div>
                        <ul id="validationWarningList" class="list-disc list-inside space-y-1"></ul>
                    </div>

                    <!-- Total Price & Add to Cart -->
                    <div class="pt-3 border-t border-gray-800 space-y-3">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-xs text-gray-400 font-bold uppercase block">Toplam Tutar</span>
                                <span class="text-[10px] text-gray-500">KDV Dahil</span>
                            </div>
                            <span id="summaryTotalPrice" class="text-2xl sm:text-3xl font-black text-yellow-400 tracking-tight">0,00 TL</span>
                        </div>

                        <button type="button" id="addToCartBtn" onclick="addBuildToCart()" disabled class="w-full bg-gradient-to-r from-yellow-500 via-amber-400 to-yellow-500 hover:from-yellow-400 hover:to-amber-300 text-afiDark font-black py-4 rounded-2xl text-sm transition-all shadow-lg shadow-yellow-500/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-cart-shopping"></i> Tüm Sistemi Sepete Ekle
                        </button>

                        <button type="button" onclick="printOrShareBuild()" class="w-full bg-gray-950 hover:bg-gray-800 text-gray-300 border border-gray-800 hover:border-gray-700 font-bold py-2 rounded-xl text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-share-nodes text-yellow-400"></i> Sistemi Paylaş / Yazdır
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<!-- Modal for Hardware Part Selection (YENİLENMİŞ & GELİŞTİRİLMİŞ MODAL) -->
<div id="partSelectionModal" class="fixed inset-0 z-[2000] hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-4">
    <div class="bg-[#111622] border border-gray-800 text-white rounded-3xl max-w-4xl w-full p-5 sm:p-7 shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex justify-between items-center pb-4 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div id="modalHeaderIcon" class="w-11 h-11 rounded-2xl bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-lg sm:text-xl font-black text-white">Parça Seçimi</h3>
                    <p id="modalSubtitle" class="text-xs text-emerald-400 flex items-center gap-1.5 mt-0.5">
                        <i class="fa-solid fa-circle-check"></i> Uyumluluk matrisine uygun ürünler filtrelendi.
                    </p>
                </div>
            </div>
            <button type="button" onclick="closePartModal()" class="text-gray-400 hover:text-white transition-colors text-lg w-9 h-9 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Filter / Search & Quick Filters Bar -->
        <div class="pt-4 pb-2 space-y-3">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <input type="text" id="partSearchInput" onkeyup="filterPartList()" placeholder="Ürün adı, model veya marka ile ara..." class="w-full bg-gray-900 border border-gray-800 focus:border-yellow-500 rounded-2xl px-4 py-2.5 pl-10 text-white text-xs sm:text-sm focus:outline-none transition-colors">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                </div>

                <!-- Sort Dropdown -->
                <div class="w-full sm:w-auto shrink-0 flex items-center gap-2">
                    <select id="partSortSelect" onchange="sortPartList()" class="w-full sm:w-auto bg-gray-900 border border-gray-800 text-gray-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-yellow-500">
                        <option value="default">Sıralama: Önerilen</option>
                        <option value="price_asc">Fiyat: Düşükten Yükseğe</option>
                        <option value="price_desc">Fiyat: Yüksekten Düşüğe</option>
                        <option value="name_asc">İsim (A-Z)</option>
                    </select>
                </div>
            </div>

            <!-- Dynamic Quick Filter Pills (Brand / Type) -->
            <div id="modalFilterPills" class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs" style="scrollbar-width:none;">
                <!-- Dynamically injected pills (e.g. All, Intel, AMD, DDR4, DDR5) -->
            </div>
        </div>

        <!-- Part List Container -->
        <div id="partListContainer" class="space-y-2.5 overflow-y-auto pr-1 flex-1 mt-2" style="scrollbar-width:thin; scrollbar-color:#374151 transparent;">
            <!-- Dynamic Part Items AJAX -->
        </div>

        <!-- Modal Bottom Counter Bar -->
        <div class="pt-3 border-t border-gray-800/80 flex items-center justify-between text-xs text-gray-400 mt-2">
            <span id="modalResultCount">0 uyumlu parça bulundu</span>
            <button type="button" onclick="closePartModal()" class="text-xs font-bold text-gray-400 hover:text-white px-3 py-1 rounded-lg hover:bg-gray-800 transition">
                Vazgeç
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const selectedParts = {
        cpu: null,
        motherboard: null,
        ram: null,
        gpu: null,
        psu: null,
        ssd: null,
        hdd: null,
        case: null
    };

    const partQuantities = {
        cpu: 1,
        motherboard: 1,
        ram: 1,
        gpu: 1,
        psu: 1,
        ssd: 1,
        hdd: 1,
        case: 1
    };

    const slotIcons = {
        cpu: 'fa-microchip',
        motherboard: 'fa-border-all',
        ram: 'fa-memory',
        gpu: 'fa-tv',
        psu: 'fa-plug',
        ssd: 'fa-bolt-lightning',
        hdd: 'fa-hard-drive',
        case: 'fa-computer'
    };

    let activeSlotKey = null;
    let activeSlotSlug = '';
    let fetchedParts = [];
    let currentBrandFilter = 'all';

    function changeQuantity(slotKey, delta) {
        if (!selectedParts[slotKey]) return;
        let current = partQuantities[slotKey] || 1;
        let next = current + delta;
        if (next < 1) next = 1;
        if (next > 4) next = 4;
        partQuantities[slotKey] = next;

        const qtyValEl = document.getElementById(`slot-qty-val-${slotKey}`);
        if (qtyValEl) qtyValEl.innerText = next;

        updateSlotPriceDisplay(slotKey);
        renderSummaryShowcase();
        validateSystem();
    }

    function updateSlotPriceDisplay(slotKey) {
        const part = selectedParts[slotKey];
        const priceEl = document.getElementById(`slot-price-${slotKey}`);
        if (part && priceEl) {
            const qty = partQuantities[slotKey] || 1;
            const unitPrice = part.price || 0;
            const totalPrice = unitPrice * qty;
            priceEl.innerText = new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + ' TL';
        }
    }

    function renderSummaryShowcase() {
        const showcaseEl = document.getElementById('summaryPartsShowcase');
        const badgeEl = document.getElementById('selectedPartsCountBadge');
        if (!showcaseEl) return;

        const slotNames = {
            cpu: 'İşlemci',
            motherboard: 'Anakart',
            ram: 'RAM Bellek',
            gpu: 'Ekran Kartı',
            psu: 'Güç Kaynağı',
            ssd: 'SSD Depolama',
            hdd: 'HDD Depolama',
            case: 'Bilgisayar Kasası'
        };

        let count = 0;
        let html = '';

        Object.keys(selectedParts).forEach(key => {
            const part = selectedParts[key];
            if (part) {
                count++;
                const qty = partQuantities[key] || 1;
                const unitPrice = part.price || 0;
                const totalPrice = unitPrice * qty;
                const formattedPrice = new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + ' TL';

                const imgHtml = part.image 
                    ? `<img src="${part.image}" class="w-10 h-10 object-contain rounded-xl bg-gray-900 border border-gray-800 p-1 shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" alt="">
                       <div class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 items-center justify-center text-yellow-400 text-sm hidden shrink-0"><i class="fa-solid ${slotIcons[key] || 'fa-cube'}"></i></div>`
                    : `<div class="w-10 h-10 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-center text-yellow-400 text-sm shrink-0"><i class="fa-solid ${slotIcons[key] || 'fa-cube'}"></i></div>`;

                html += `
                    <div class="flex items-center justify-between gap-3 bg-gray-950/80 border border-gray-800 p-2.5 rounded-2xl hover:border-gray-700 transition-all">
                        <div class="flex items-center gap-2.5 min-w-0">
                            ${imgHtml}
                            <div class="min-w-0">
                                <span class="text-[10px] text-yellow-500 font-bold uppercase tracking-wider block truncate">${slotNames[key] || key}</span>
                                <h6 class="text-xs font-bold text-gray-200 truncate leading-tight">${part.title}</h6>
                                <span class="text-[10px] text-gray-400 font-bold bg-gray-900 px-1.5 py-0.5 rounded border border-gray-800 inline-block mt-0.5">${qty}x Adet</span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-black text-yellow-400 block">${formattedPrice}</span>
                        </div>
                    </div>
                `;
            }
        });

        if (count > 0) {
            showcaseEl.innerHTML = html;
            if (badgeEl) badgeEl.innerText = `${count} Parça`;
        } else {
            showcaseEl.innerHTML = `
                <div class="text-center py-6 bg-gray-950/50 rounded-2xl border border-dashed border-gray-800 text-gray-500 text-xs">
                    <i class="fa-solid fa-cube text-xl mb-1.5 block text-gray-600"></i>
                    Henüz parça eklenmedi.
                </div>
            `;
            if (badgeEl) badgeEl.innerText = `0 Parça`;
        }

        updateProgressBar(count);
    }

    function updateProgressBar(count) {
        const percent = Math.round((count / 8) * 100);
        const bar = document.getElementById('buildProgressBar');
        const badge = document.getElementById('progressCountBadge');
        if (bar) bar.style.width = `${percent}%`;
        if (badge) badge.innerText = `${count} / 8 Parça Seçildi (%${percent})`;
    }

    async function openPartModal(slotKey, slotName, slotSlug) {
        activeSlotKey = slotKey;
        activeSlotSlug = slotSlug;
        currentBrandFilter = 'all';

        const iconEl = document.getElementById('modalHeaderIcon');
        if (iconEl) iconEl.innerHTML = `<i class="fa-solid ${slotIcons[slotKey] || 'fa-microchip'}"></i>`;

        document.getElementById('modalTitle').innerText = `${slotName} Seçin`;
        document.getElementById('partSearchInput').value = '';
        document.getElementById('partSortSelect').value = 'default';
        
        let subtitle = '<i class="fa-solid fa-circle-check text-emerald-400"></i> Uyumluluk matrisine göre filtrelendi.';
        if (slotKey === 'motherboard' && selectedParts.cpu && selectedParts.cpu.socket) {
            subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Sadece <strong>${selectedParts.cpu.socket}</strong> soket mimarisiyle uyumlu anakartlar listeleniyor.`;
        } else if (slotKey === 'ram' && selectedParts.motherboard && selectedParts.motherboard.ram_type) {
            subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Sadece <strong>${selectedParts.motherboard.ram_type}</strong> desteği olan RAM bellekler listeleniyor.`;
        }
        document.getElementById('modalSubtitle').innerHTML = subtitle;

        const modal = document.getElementById('partSelectionModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const container = document.getElementById('partListContainer');
        container.innerHTML = `<div class="py-16 text-center text-yellow-500"><i class="fa-solid fa-spinner fa-spin text-3xl"></i><p class="text-xs text-gray-400 mt-2 font-bold">Uyumlu parçalar kontrol ediliyor...</p></div>`;

        const partsMap = getSelectedPartsMap();
        const cpuSocket = selectedParts.cpu ? (selectedParts.cpu.socket || '') : (selectedParts.motherboard ? (selectedParts.motherboard.socket || '') : '');
        const ramType   = selectedParts.motherboard ? (selectedParts.motherboard.ram_type || '') : (selectedParts.ram ? (selectedParts.ram.ram_type || '') : '');

        try {
            const queryParams = new URLSearchParams({
                category_slug: slotSlug,
                cpu_socket: cpuSocket,
                ram_type: ramType,
                selected_parts: JSON.stringify(partsMap)
            });
            const res = await fetch(`{{ route('pc-builder.parts') }}?${queryParams.toString()}`);
            const data = await res.json();

            if (data.success && data.products.length > 0) {
                // Dinamik Bilgi Bildirimi (Subtitle) Güncellemesi
                if (slotKey === 'psu' && data.min_required_psu_watt > 0) {
                    subtitle = `<i class="fa-solid fa-bolt text-yellow-400"></i> Sisteminizin tahmini <strong>${data.total_tdp}W</strong> tüketimine uygun, en az <strong>${data.min_required_psu_watt}W</strong> kapasiteli güç kaynakları listeleniyor.`;
                } else if (slotKey === 'motherboard' && data.active_socket) {
                    subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Sadece <strong>${data.active_socket}</strong> soket mimarisiyle uyumlu anakartlar listeleniyor.`;
                } else if (slotKey === 'cpu' && data.active_socket) {
                    subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Sadece <strong>${data.active_socket}</strong> soket mimarisiyle uyumlu işlemciler listeleniyor.`;
                } else if (slotKey === 'ram' && data.active_ram_type) {
                    subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Sadece <strong>${data.active_ram_type}</strong> desteği olan RAM bellekler listeleniyor.`;
                } else if (slotKey === 'case' || slotKey === 'gpu') {
                    subtitle = `<i class="fa-solid fa-circle-check text-emerald-400"></i> Kasa boyut ve ekran kartı uzunluk uyumluluğuna göre filtrelendi.`;
                }
                document.getElementById('modalSubtitle').innerHTML = subtitle;

                fetchedParts = data.products;
                buildFilterPills(fetchedParts);
                renderPartList(fetchedParts);
            } else {
                fetchedParts = [];
                buildFilterPills([]);
                if (slotKey === 'psu' && data.min_required_psu_watt > 0) {
                    subtitle = `<i class="fa-solid fa-triangle-exclamation text-amber-400"></i> En az <strong>${data.min_required_psu_watt}W</strong> gerektiren sisteminiz için stokta uygun PSU bulunamadı.`;
                    document.getElementById('modalSubtitle').innerHTML = subtitle;
                }
                container.innerHTML = `
                    <div class="py-14 text-center text-gray-500">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-600"></i>
                        <p class="text-sm font-bold text-gray-300">Bu uyumluluk kriterinde ürün bulunamadı.</p>
                        <p class="text-xs text-gray-500 mt-1">İşlemci veya anakart soket seçiminizi değiştirmeyi deneyebilirsiniz.</p>
                    </div>`;
                document.getElementById('modalResultCount').innerText = '0 ürün bulundu';
            }
        } catch (err) {
            container.innerHTML = `<div class="py-14 text-center text-red-400"><p class="text-xs font-bold">Parçalar yüklenirken bir hata oluştu.</p></div>`;
        }
    }

    function closePartModal() {
        document.getElementById('partSelectionModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function buildFilterPills(parts) {
        const pillsContainer = document.getElementById('modalFilterPills');
        if (!pillsContainer) return;

        if (parts.length === 0) {
            pillsContainer.innerHTML = '';
            return;
        }

        // Extract brands & platforms
        const brands = new Set();
        parts.forEach(p => {
            if (p.platform) brands.add(p.platform.toUpperCase());
            if (p.ram_type) brands.add(p.ram_type.toUpperCase());
            if (p.brand) brands.add(p.brand.toUpperCase());
        });

        let html = `<button type="button" onclick="setBrandFilter('all')" class="modal-pill px-3 py-1 rounded-full font-bold text-xs transition cursor-pointer ${currentBrandFilter === 'all' ? 'bg-yellow-500 text-afiDark' : 'bg-gray-900 hover:bg-gray-800 text-gray-300 border border-gray-800'}">Tümü (${parts.length})</button>`;

        Array.from(brands).slice(0, 6).forEach(tag => {
            const count = parts.filter(p => 
                (p.platform && p.platform.toUpperCase() === tag) || 
                (p.ram_type && p.ram_type.toUpperCase() === tag) ||
                (p.brand && p.brand.toUpperCase() === tag)
            ).length;
            
            if (count > 0) {
                const isActive = currentBrandFilter === tag;
                html += `<button type="button" onclick="setBrandFilter('${tag}')" class="modal-pill px-3 py-1 rounded-full font-bold text-xs transition cursor-pointer ${isActive ? 'bg-yellow-500 text-afiDark' : 'bg-gray-900 hover:bg-gray-800 text-gray-300 border border-gray-800'}">${tag} (${count})</button>`;
            }
        });

        pillsContainer.innerHTML = html;
    }

    function setBrandFilter(tag) {
        currentBrandFilter = tag;
        buildFilterPills(fetchedParts);
        filterPartList();
    }

    function filterPartList() {
        const query = document.getElementById('partSearchInput').value.toLowerCase().trim();
        let filtered = fetchedParts.filter(p => {
            const matchQuery = p.title.toLowerCase().includes(query) || (p.brand && p.brand.toLowerCase().includes(query));
            if (!matchQuery) return false;

            if (currentBrandFilter !== 'all') {
                const tag = currentBrandFilter.toUpperCase();
                const matchTag = (p.platform && p.platform.toUpperCase() === tag) ||
                                 (p.ram_type && p.ram_type.toUpperCase() === tag) ||
                                 (p.brand && p.brand.toUpperCase() === tag);
                if (!matchTag) return false;
            }

            return true;
        });

        sortAndRenderList(filtered);
    }

    function sortPartList() {
        filterPartList();
    }

    function sortAndRenderList(list) {
        const sortVal = document.getElementById('partSortSelect').value;
        if (sortVal === 'price_asc') {
            list.sort((a, b) => a.price - b.price);
        } else if (sortVal === 'price_desc') {
            list.sort((a, b) => b.price - a.price);
        } else if (sortVal === 'name_asc') {
            list.sort((a, b) => a.title.localeCompare(b.title));
        }

        renderPartList(list);
    }

    function renderPartList(parts) {
        const container = document.getElementById('partListContainer');
        const countEl = document.getElementById('modalResultCount');
        container.innerHTML = '';

        if (countEl) countEl.innerText = `${parts.length} uyumlu parça listeleniyor`;

        if (parts.length === 0) {
            container.innerHTML = `
                <div class="py-12 text-center text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-3xl mb-2 text-gray-600"></i>
                    <p class="text-sm font-bold text-gray-300">Aramanıza uygun ürün bulunamadı.</p>
                    <button type="button" onclick="document.getElementById('partSearchInput').value=''; setBrandFilter('all');" class="text-xs text-yellow-400 font-bold mt-2 underline">Filtreleri Sıfırla</button>
                </div>`;
            return;
        }

        parts.forEach(part => {
            let metaTags = '';
            if (part.platform) {
                const pBg = part.platform.toUpperCase() === 'INTEL' 
                    ? 'bg-blue-600/20 text-blue-300 border-blue-500/40' 
                    : (part.platform.toUpperCase() === 'AMD' ? 'bg-red-600/20 text-red-300 border-red-500/40' : 'bg-emerald-600/20 text-emerald-300 border-emerald-500/40');
                metaTags += `<span class="${pBg} px-2 py-0.5 rounded-md border text-[10px] font-black">${part.platform}</span>`;
            }
            if (part.socket) metaTags += `<span class="bg-gray-800 text-yellow-400 px-2 py-0.5 rounded-md border border-gray-700 text-[10px] font-bold">Soket: ${part.socket}</span>`;
            if (part.ram_type) metaTags += `<span class="bg-gray-800 text-purple-300 px-2 py-0.5 rounded-md border border-gray-700 text-[10px] font-bold">RAM: ${part.ram_type}</span>`;
            if (part.tdp_watt > 0) metaTags += `<span class="bg-gray-800 text-amber-400 px-2 py-0.5 rounded-md border border-gray-700 text-[10px] font-bold"><i class="fa-solid fa-bolt text-[9px]"></i> ${part.tdp_watt}W</span>`;

            const isSelected = selectedParts[activeSlotKey] && selectedParts[activeSlotKey].id === part.id;

            // Safe image rendering (Never broken placeholder)
            const iconFallback = slotIcons[activeSlotKey] || 'fa-microchip';
            const imgHtml = part.image 
                ? `<div class="w-14 h-14 rounded-2xl bg-gray-950 border border-gray-800 p-1 flex items-center justify-center shrink-0 overflow-hidden relative group/thumb">
                     <img src="${part.image}" class="w-full h-full object-contain transition-transform group-hover/thumb:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" alt="">
                     <div class="w-full h-full items-center justify-center text-yellow-400 text-xl hidden"><i class="fa-solid ${iconFallback}"></i></div>
                   </div>`
                : `<div class="w-14 h-14 rounded-2xl bg-gray-950 border border-gray-800 flex items-center justify-center text-yellow-400 text-xl shrink-0"><i class="fa-solid ${iconFallback}"></i></div>`;

            const div = document.createElement('div');
            div.className = `p-3.5 sm:p-4 rounded-2xl border transition-all duration-200 flex items-center justify-between gap-4 ${isSelected ? 'bg-yellow-500/10 border-yellow-500 shadow-md shadow-yellow-500/10' : 'bg-gray-900/90 border-gray-800 hover:border-gray-700 hover:bg-gray-900'}`;
            div.innerHTML = `
                <div class="flex items-center gap-3.5 min-w-0">
                    ${imgHtml}
                    <div class="min-w-0">
                        <h5 class="text-xs sm:text-sm font-bold text-white leading-snug line-clamp-2">${part.title}</h5>
                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">${metaTags}</div>
                    </div>
                </div>
                <div class="text-right shrink-0 flex flex-col items-end gap-1.5">
                    <div class="text-sm sm:text-base font-black text-yellow-400 tracking-tight">${part.price_formatted}</div>
                    <button type="button" onclick='selectPart(${JSON.stringify(part)})' class="${isSelected ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950' : 'bg-yellow-500 hover:bg-yellow-400 text-afiDark'} font-black px-4 py-1.5 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5 shadow-sm">
                        ${isSelected ? '<i class="fa-solid fa-check"></i> Seçildi' : '<i class="fa-solid fa-plus text-[10px]"></i> Ekle'}
                    </button>
                </div>
            `;
            container.appendChild(div);
        });
    }

    function selectPart(part) {
        selectedParts[activeSlotKey] = part;
        partQuantities[activeSlotKey] = 1;
        closePartModal();
        updateSlotUI(activeSlotKey);
        validateSystem();
    }

    function removePart(slotKey) {
        selectedParts[slotKey] = null;
        partQuantities[slotKey] = 1;
        updateSlotUI(slotKey);
        validateSystem();
    }

    function updateSlotUI(slotKey) {
        const part = selectedParts[slotKey];
        const cardEl = document.getElementById(`slot-card-${slotKey}`);
        const nameEl = document.getElementById(`slot-name-${slotKey}`);
        const priceEl = document.getElementById(`slot-price-${slotKey}`);
        const removeBtn = document.getElementById(`slot-remove-${slotKey}`);
        const btnTextEl = document.getElementById(`slot-btn-text-${slotKey}`);
        const btnIconEl = document.getElementById(`slot-btn-icon-${slotKey}`);
        const metaEl = document.getElementById(`slot-meta-${slotKey}`);
        const qtyContainer = document.getElementById(`slot-qty-container-${slotKey}`);
        const qtyValEl = document.getElementById(`slot-qty-val-${slotKey}`);
        const imgEl = document.getElementById(`slot-img-${slotKey}`);

        if (part) {
            nameEl.innerText = part.title;
            nameEl.className = "text-xs sm:text-sm md:text-base font-bold text-white truncate max-w-full";
            
            updateSlotPriceDisplay(slotKey);
            priceEl.classList.remove('hidden');
            removeBtn.classList.remove('hidden');
            removeBtn.classList.add('flex');
            btnTextEl.innerText = 'Değiştir';
            if (btnIconEl) btnIconEl.className = 'fa-solid fa-rotate text-xs';

            if (cardEl) {
                cardEl.classList.add('border-yellow-500/40', 'bg-gray-900');
                cardEl.classList.remove('border-gray-800');
            }

            if (imgEl) {
                if (part.image) {
                    imgEl.src = part.image;
                    imgEl.classList.remove('hidden');
                    imgEl.onerror = function() { this.classList.add('hidden'); };
                } else {
                    imgEl.classList.add('hidden');
                }
            }

            if (qtyContainer) {
                if (['ram', 'ssd', 'hdd'].includes(slotKey)) {
                    qtyContainer.classList.remove('hidden');
                    qtyContainer.classList.add('flex');
                    if (qtyValEl) qtyValEl.innerText = partQuantities[slotKey] || 1;
                } else {
                    qtyContainer.classList.add('hidden');
                    qtyContainer.classList.remove('flex');
                }
            }

            let metaStr = '';
            if (part.socket) metaStr += `<span class="bg-gray-800 text-yellow-400 px-2 py-0.5 rounded border border-gray-700">Soket: ${part.socket}</span>`;
            if (part.ram_type) metaStr += `<span class="bg-gray-800 text-purple-300 px-2 py-0.5 rounded border border-gray-700">RAM: ${part.ram_type}</span>`;
            if (part.tdp_watt > 0) metaStr += `<span class="bg-gray-800 text-amber-400 px-2 py-0.5 rounded border border-gray-700">${part.tdp_watt}W</span>`;

            if (metaStr) {
                metaEl.innerHTML = metaStr;
                metaEl.classList.remove('hidden');
            } else {
                metaEl.classList.add('hidden');
            }
        } else {
            nameEl.innerText = 'Parça seçilmedi';
            nameEl.className = "text-xs sm:text-sm md:text-base font-bold text-gray-400 truncate max-w-full";
            priceEl.classList.add('hidden');
            removeBtn.classList.add('hidden');
            removeBtn.classList.remove('flex');
            btnTextEl.innerText = 'Parça Seç';
            if (btnIconEl) btnIconEl.className = 'fa-solid fa-plus text-xs';
            metaEl.classList.add('hidden');
            if (imgEl) imgEl.classList.add('hidden');
            if (qtyContainer) {
                qtyContainer.classList.add('hidden');
                qtyContainer.classList.remove('flex');
            }
            if (cardEl) {
                cardEl.classList.remove('border-yellow-500/40');
                cardEl.classList.add('border-gray-800');
            }
        }

        renderSummaryShowcase();
    }

    function getSelectedPartsMap() {
        const map = {};
        Object.keys(selectedParts).forEach(key => {
            if (selectedParts[key]) {
                map[key] = selectedParts[key].id;
            }
        });
        return map;
    }

    function resetEntireBuild() {
        if (!confirm('Seçtiğiniz tüm donanım parçalarını sıfırlamak istediğinize emin misiniz?')) return;
        Object.keys(selectedParts).forEach(key => {
            selectedParts[key] = null;
            partQuantities[key] = 1;
            updateSlotUI(key);
        });
        validateSystem();
    }

    async function validateSystem() {
        const partsMap = getSelectedPartsMap();
        const partsArray = Object.values(partsMap);
        
        // Dynamic summary updates
        document.getElementById('summaryCpuSocket').innerText = selectedParts.cpu ? (selectedParts.cpu.socket || 'Belirtilmedi') : '-';
        document.getElementById('summaryRamType').innerText = selectedParts.motherboard ? (selectedParts.motherboard.ram_type || 'Belirtilmedi') : '-';

        const topChip = document.getElementById('topCompatibilityChip');

        if (partsArray.length === 0) {
            document.getElementById('summaryTotalPrice').innerText = '0,00 TL';
            document.getElementById('summaryTdp').innerText = '0 Watt';
            document.getElementById('summaryMinPsu').innerText = 'En az 0W';
            document.getElementById('validationErrorBox').classList.add('hidden');
            document.getElementById('validationWarningBox').classList.add('hidden');
            document.getElementById('addToCartBtn').disabled = true;

            if (topChip) {
                topChip.className = 'bg-gray-800/80 text-gray-400 border border-gray-700 text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-2';
                topChip.innerHTML = '<span class="w-2 h-2 rounded-full bg-gray-500"></span> Parça Seçimi Bekleniyor';
            }
            return;
        }

        try {
            const res = await fetch(`{{ route('pc-builder.validate') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ parts: partsMap, quantities: partQuantities })
            });

            const data = await res.json();

            document.getElementById('summaryTotalPrice').innerText = data.total_price;
            document.getElementById('summaryTdp').innerText = `${data.total_tdp} Watt`;
            document.getElementById('summaryMinPsu').innerText = `En az ${data.min_psu_watt}W`;

            const badge = document.getElementById('compatibilityBadge');
            const errBox = document.getElementById('validationErrorBox');
            const errList = document.getElementById('validationErrorList');
            const warnBox = document.getElementById('validationWarningBox');
            const warnList = document.getElementById('validationWarningList');
            const cartBtn = document.getElementById('addToCartBtn');

            if (data.is_compatible) {
                badge.className = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-[10px] font-black px-2.5 py-1 rounded-full uppercase flex items-center gap-1 shadow-sm';
                badge.innerHTML = `<i class="fa-solid fa-check"></i> Uyumlu`;
                errBox.classList.add('hidden');
                cartBtn.disabled = false;

                if (topChip) {
                    topChip.className = 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-2';
                    topChip.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sistem Bileşenleri Uyumlu';
                }
            } else {
                badge.className = 'bg-red-500/20 text-red-400 border border-red-500/30 text-[10px] font-black px-2.5 py-1 rounded-full uppercase flex items-center gap-1 shadow-sm animate-pulse';
                badge.innerHTML = `<i class="fa-solid fa-xmark"></i> Uyumsuz Parça!`;
                
                errList.innerHTML = '';
                data.errors.forEach(err => {
                    const li = document.createElement('li');
                    li.innerText = err;
                    errList.appendChild(li);
                });
                errBox.classList.remove('hidden');
                cartBtn.disabled = true;

                if (topChip) {
                    topChip.className = 'bg-red-500/15 text-red-400 border border-red-500/30 text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-2';
                    topChip.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span> Uyumsuzluk Var';
                }
            }

            if (data.warnings && data.warnings.length > 0) {
                warnList.innerHTML = '';
                data.warnings.forEach(warn => {
                    const li = document.createElement('li');
                    li.innerText = warn;
                    warnList.appendChild(li);
                });
                warnBox.classList.remove('hidden');
            } else {
                warnBox.classList.add('hidden');
            }

        } catch (err) {
            console.error('Validation error:', err);
        }
    }

    async function addBuildToCart() {
        const partsMap = getSelectedPartsMap();
        if (Object.keys(partsMap).length === 0) return;

        const cartBtn = document.getElementById('addToCartBtn');
        cartBtn.disabled = true;
        cartBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Sepete Ekleniyor...`;

        try {
            const res = await fetch(`{{ route('pc-builder.add-to-cart') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ parts: partsMap, quantities: partQuantities })
            });

            const data = await res.json();
            cartBtn.innerHTML = `<i class="fa-solid fa-cart-shopping"></i> Tüm Sistemi Sepete Ekle`;

            if (data.success) {
                alert(data.message);
                window.location.href = "{{ route('cart.index') }}";
            } else {
                alert(data.message || 'Sisteminizde uyumsuz parçalar bulunmaktadır!');
                cartBtn.disabled = false;
            }
        } catch (err) {
            cartBtn.innerHTML = `<i class="fa-solid fa-cart-shopping"></i> Tüm Sistemi Sepete Ekle`;
            cartBtn.disabled = false;
            alert('Sepete eklenirken bir sunucu hatası oluştu.');
        }
    }

    function printOrShareBuild() {
        const partsMap = getSelectedPartsMap();
        const count = Object.keys(partsMap).length;
        if (count === 0) {
            alert('Lütfen önce sisteminize parça ekleyin.');
            return;
        }
        window.print();
    }
</script>
@endpush
