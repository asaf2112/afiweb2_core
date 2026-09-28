@php
if(isset($product) && is_object($product)) {
    $p_id = $product->id;
    $title = $product->title;
    $price = number_format($product->price, 0, ',', '.');
    $img = \Illuminate\Support\Str::startsWith($product->main_image, ['http://', 'https://']) ? $product->main_image : asset($product->main_image);
    $conditionBadge = (isset($product) && $product->condition_type == 'new') || ($badge ?? '') == 'Sıfır' ? 'Sıfır' : 'İkinci El';
    $slug = $product->slug;
} else {
    $p_id = $id ?? 0;
    $conditionBadge = ($badge ?? '') == 'Sıfır' ? 'Sıfır' : 'İkinci El';
    $slug = \Illuminate\Support\Str::slug($title);
}
$badge = $conditionBadge;


$cardSpecs = [];
if(isset($product) && !empty($product->specs)) {
    $cardSpecs = is_array($product->specs) ? $product->specs : json_decode($product->specs, true) ?? [];
}

// Normalize specs (handles both flat {"cpu":"i5"} and array [{"key":"cpu", "value":"i5"}])
$normalizedSpecs = [];
foreach($cardSpecs as $k => $v) {
    if(is_array($v) && isset($v['key'])) {
        $normalizedSpecs[strtolower($v['key'])] = $v['value'] ?? '';
    } elseif(is_array($v) && isset($v['name'])) {
        $normalizedSpecs[strtolower($v['name'])] = $v['value'] ?? '';
    } elseif(is_string($k)) {
        $normalizedSpecs[strtolower($k)] = $v;
    }
}

$keyMap = [
    'cpu'                 => 'İşlemci',
    'processor'           => 'İşlemci',
    'cpu_model'           => 'İşlemci',
    'ram'                 => 'RAM',
    'memory'              => 'RAM',
    'ram_type'            => 'RAM Tipi',
    'gpu'                 => 'Ekran Kartı',
    'graphics'            => 'Ekran Kartı',
    'graphics_card'       => 'Ekran Kartı',
    'gpu_chipset'         => 'GPU Çipi',
    'storage'             => 'Depolama',
    'capacity'            => 'Kapasite',
    'read_speed'          => 'Okuma Hızı',
    'write_speed'         => 'Yazma Hızı',
    'rpm'                 => 'RPM',
    'type'                => 'Tip',
    'motherboard_support' => 'Anakart',
    'radiator_support'    => 'Radyatör',
    'max_gpu_length'      => 'Maks. GPU',
    'brand'               => 'Marka',
    'chipset'             => 'Çip Seti',
    'socket'              => 'Soket',
    'psu'                 => 'Güç Kaynağı',
    'power_supply'        => 'Güç Kaynağı',
    'wattage'             => 'Güç (W)',
    'screen_size'         => 'Ekran Boyutu',
    'refresh_rate'        => 'Yenileme Hızı',
    'response_time'       => 'Tepki Süresi',
    'panel_type'          => 'Panel',
    'resolution'          => 'Çözünürlük',
    'interface'           => 'Bağlantı',
    'form_factor'         => 'Form Faktörü',
    'warranty'            => 'Garanti',
    'color'               => 'Renk',
    'weight'              => 'Ağırlık',
    'cooling'             => 'Soğutma',
    'fan_size'            => 'Fan',
    'rgb'                 => 'RGB',
    'is_gaming'           => 'Oyuncu Serisi',
    'os'                  => 'İşletim Sistemi',
    'battery'             => 'Batarya',
    'camera'              => 'Kamera',
    'lens'                => 'Lens',
    'sensor'              => 'Sensör'
];
@endphp

@php
    $isGaming = isset($product) && is_object($product) && $product->is_gaming_pc;
@endphp

{{-- OUTER FLIP CARD WRAPPER --}}
<div class="flip-card product-card" onclick="toggleCard(this)"
     style="position:relative; cursor:pointer; width:100%; height:420px; min-height:420px; max-height:420px; min-width:0; margin:0; box-sizing:border-box; perspective:1000px; overflow:hidden;">

    {{-- INNER ROTATING CONTAINER --}}
    <div class="flip-inner"
         style="position:relative; width:100%; height:100%; transform-style:preserve-3d; transition:transform 0.7s cubic-bezier(0.4,0,0.2,1);">

        {{-- ── FRONT FACE ── --}}
        <div style="position:absolute; inset:0; width:100%; height:100%; backface-visibility:hidden; -webkit-backface-visibility:hidden; background:#161a23; border:1px solid {{ $isGaming ? 'rgba(168,85,247,0.4)' : ($conditionBadge === 'İkinci El' ? 'rgba(16,185,129,0.5)' : '#1f2937') }}; border-radius:1rem; padding:1.25rem; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); display:flex; flex-direction:column; justify-content:space-between; overflow:hidden; box-sizing:border-box;">

            @if($isGaming)
                {{-- Gaming Neon Accent Bar --}}
                <div style="position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#9333ea,#ec4899,#06b6d4); z-index:30;"></div>
            @elseif($conditionBadge === 'İkinci El')
                {{-- Second Hand Accent Bar --}}
                <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#10b981,#059669,#14b8a6); z-index:30;"></div>
            @endif

            {{-- Badges --}}
            <div class="absolute top-4 left-4 z-10 flex flex-wrap items-center gap-1.5 max-w-[75%] pointer-events-none">
                @if($conditionBadge === 'İkinci El')
                    <span class="bg-gradient-to-r from-emerald-600 to-teal-500 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-md border border-emerald-400/50">
                        <i class="fa-solid fa-recycle text-[10px]"></i> İkinci El
                    </span>
                @endif
                @if($isGaming)
                    <span class="bg-gradient-to-r from-purple-600 via-indigo-600 to-cyan-500 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-lg shadow-purple-500/30 border border-purple-400/50">
                        <i class="fa-solid fa-gamepad text-cyan-300 text-[10px]"></i> GAMING PC
                    </span>
                @endif
                @if(isset($product) && !empty($product->badge))
                    @if(!empty($product->badge_style['badge_html']))
                        {!! $product->badge_style['badge_html'] !!}
                    @else
                        <span class="bg-gradient-to-r from-yellow-500 to-amber-500 text-afiDark text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-md border border-yellow-400/50">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ $product->badge }}
                        </span>
                    @endif
                @elseif(isset($badge_text) && !empty($badge_text))
                    <span class="bg-gradient-to-r from-yellow-500 to-amber-500 text-afiDark text-[10px] font-black px-2.5 py-0.5 rounded-full flex items-center gap-1 shadow-md border border-yellow-400/50">
                        <i class="fa-solid fa-star text-[10px]"></i> {{ $badge_text }}
                    </span>
                @endif
                @if(isset($product) && $product->stock > 0 && $product->stock <= 5)
                    <div class="bg-amber-400 text-afiDark text-[10px] font-black px-2 py-0.5 rounded-full flex items-center gap-1 shadow-md border border-amber-300 animate-pulse">
                        <i class="fa-solid fa-fire text-red-600 text-[10px]"></i> Son {{ $product->stock }} Ürün!
                    </div>
                @elseif(isset($product) && $product->stock <= 0)
                    <div class="bg-red-500/20 text-red-400 border-red-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow-sm border backdrop-blur-md">
                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Stokta Yok
                    </div>
                @endif
            </div>

            {{-- Favorite Button --}}
            @php
                $isFavorited = auth()->check() && $p_id > 0 && auth()->user()->favorites()->where('product_id', $p_id)->exists();
            @endphp
            <button type="button" class="favorite-toggle-btn absolute top-4 right-4 z-50 w-8 h-8 bg-gray-800/80 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm border border-gray-700 text-{{ $isFavorited ? 'red-500' : 'gray-400' }} hover:scale-110 hover:text-red-500 transition-all" data-product-id="{{ $p_id }}" onclick="event.stopPropagation(); toggleFavorite(this, {{ $p_id }});">
                <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart text-sm"></i>
            </button>

            {{-- Image --}}
            <div class="bg-gray-900/50 rounded-xl mb-4 overflow-hidden relative h-48 flex items-center justify-center p-3 border border-gray-800/50">
                <img src="{{ $img }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80';" alt="{{ $title }}" class="object-contain h-full w-full drop-shadow-xl" loading="lazy" decoding="async">
            </div>

            {{-- Content --}}
            <div class="flex-1 flex flex-col justify-end">
                <p class="text-[10px] text-yellow-500/80 uppercase tracking-wider mb-1 font-semibold">{{ isset($product) && $product->category ? $product->category->name : 'Kategori' }}</p>
                <h3 class="font-bold text-[15px] leading-snug text-white mb-2 line-clamp-2" title="{{ $title }}">{{ $title }}</h3>

                @if(!empty($normalizedSpecs) && (isset($normalizedSpecs['cpu']) || isset($normalizedSpecs['ram'])))
                    <div class="flex flex-wrap gap-2 mb-3 mt-1">
                        @if(isset($normalizedSpecs['cpu']) && !empty($normalizedSpecs['cpu']))
                            <span class="text-[11px] bg-gray-800/60 text-gray-300 px-2.5 py-1 rounded-md border border-gray-700/50 truncate max-w-full" title="{{ is_array($normalizedSpecs['cpu']) ? implode(', ', $normalizedSpecs['cpu']) : $normalizedSpecs['cpu'] }}">
                                <i class="fa-solid fa-microchip text-yellow-500/80 mr-1"></i> {{ is_array($normalizedSpecs['cpu']) ? $normalizedSpecs['cpu'][0] : $normalizedSpecs['cpu'] }}
                            </span>
                        @endif
                        @if(isset($normalizedSpecs['ram']) && !empty($normalizedSpecs['ram']))
                            <span class="text-[11px] bg-gray-800/60 text-gray-300 px-2.5 py-1 rounded-md border border-gray-700/50 truncate max-w-full" title="{{ is_array($normalizedSpecs['ram']) ? implode(', ', $normalizedSpecs['ram']) : $normalizedSpecs['ram'] }}">
                                <i class="fa-solid fa-memory text-yellow-500/80 mr-1"></i> {{ is_array($normalizedSpecs['ram']) ? $normalizedSpecs['ram'][0] : $normalizedSpecs['ram'] }}
                            </span>
                        @endif
                    </div>
                @else
                    @if(isset($product) && $product->description)
                        <p class="text-xs text-gray-400 mb-3 mt-1 line-clamp-2 overflow-hidden">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 80) }}</p>
                    @else
                        <p class="text-xs text-gray-400 mb-3 mt-1 line-clamp-2 overflow-hidden">Ürün detayları ve teknik özellikler için tıklayın.</p>
                    @endif
                @endif

                <div class="flex justify-between items-end mt-auto pt-4 border-t border-gray-800 shrink-0">
                    <div>
                        <p class="text-[10px] text-gray-500 mb-0.5">KDV Dahil</p>
                        @if(isset($product) && $product->is_discount_active)
                            <div class="flex flex-col leading-tight">
                                <span class="text-sm font-bold text-gray-500 line-through">{{ number_format($product->price, 2, ',', '.') }} ₺</span>
                                <span class="text-2xl font-black text-emerald-400">{{ number_format($product->discount_price, 2, ',', '.') }} ₺</span>
                            </div>
                        @else
                            <span class="text-2xl font-black text-white">{{ $price }} ₺</span>
                        @endif
                        @if(isset($product))
                            <div class="mt-1">
                                <span class="text-[9px] {{ $product->shipping_info['badge_class'] }} px-2 py-0.5 rounded-full font-bold inline-flex items-center gap-1 border">
                                    <i class="fa-solid {{ $product->shipping_info['icon'] }}"></i> {{ $product->shipping_info['title'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 relative z-50">
                        <button type="button" class="text-gray-400 hover:text-yellow-400 transition-colors" onclick="event.stopPropagation(); toggleCompare({{ $p_id }});" title="Karşılaştır">
                            <i class="fa-solid fa-scale-balanced text-base"></i>
                        </button>
                        <div class="text-gray-500 hover:text-yellow-500 transition-colors" title="Özellikler">
                            <i class="fa-solid fa-rotate text-lg"></i>
                        </div>
                        <button type="button" class="bg-yellow-500 hover:bg-yellow-400 text-afiDark w-10 h-10 rounded-lg flex items-center justify-center transition-colors shadow-lg shadow-yellow-500/20 card-add-to-cart relative z-50 block" data-id="{{ $p_id }}" onclick="event.stopPropagation(); addToCartQuick(this, {{ $p_id }})" title="Sepete Ekle">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BACK FACE ── --}}
        <div style="position:absolute; inset:0; width:100%; height:100%; backface-visibility:hidden; -webkit-backface-visibility:hidden; transform:rotateY(180deg); background:#0f172a; color:white; border-radius:1rem; padding:1.15rem 1rem 0.9rem 1rem; border:2px solid {{ $conditionBadge === 'İkinci El' ? '#10b981' : '#eab308' }}; display:flex; flex-direction:column; box-shadow:0 20px 25px -5px rgba(0,0,0,0.3); z-index:10; box-sizing:border-box;">
            <div class="flex items-center justify-between border-b border-gray-700/80 pb-2 mb-2.5 shrink-0">
                <h3 class="font-bold text-sm sm:text-base {{ $conditionBadge === 'İkinci El' ? 'text-emerald-400' : 'text-yellow-400' }} flex items-center gap-1.5">
                    <i class="fa-solid fa-microchip"></i> Hızlı Bakış
                </h3>
                <span class="text-[10px] text-gray-400 flex items-center gap-1 font-medium bg-gray-800/80 px-2 py-0.5 rounded-full border border-gray-700/50">
                    <i class="fa-solid fa-rotate-left {{ $conditionBadge === 'İkinci El' ? 'text-emerald-400' : 'text-yellow-400' }}"></i> Geri Dön
                </span>
            </div>

            <div class="flex-1 overflow-y-auto pr-1" style="scrollbar-width:thin; scrollbar-color:#374151 transparent;">
                @if(!empty($normalizedSpecs))
                    @foreach($normalizedSpecs as $key => $value)
                        @if(!empty($value) || $value === 0 || $value === '0')
                            @php
                                $kLower = strtolower($key);
                                $specLabel = $keyMap[$kLower] ?? ucwords(str_replace(['_', '-'], ' ', $key));
                                if (is_array($value)) {
                                    $valStr = implode(', ', $value);
                                } elseif (is_bool($value)) {
                                    $valStr = $value ? 'Evet' : 'Hayır';
                                } elseif (($kLower === 'is_gaming' || $kLower === 'oyuncu_serisi') && ($value === '1' || $value === 1)) {
                                    $valStr = 'Evet';
                                } elseif (($kLower === 'is_gaming' || $kLower === 'oyuncu_serisi') && ($value === '0' || $value === 0)) {
                                    $valStr = 'Hayır';
                                } else {
                                    $valStr = (string)$value;
                                }
                            @endphp
                            <div class="bg-gray-800/50 hover:bg-gray-800/70 rounded-lg px-2.5 py-1.5 border border-gray-700/50 flex items-center justify-between gap-2 mb-1.5 transition-colors">
                                <span class="text-[10px] sm:text-[11px] text-amber-400/90 font-bold uppercase tracking-wider shrink-0">{{ $specLabel }}</span>
                                <span class="text-[11px] sm:text-xs text-white font-bold text-right leading-tight break-words line-clamp-2 max-w-[65%]" title="{{ $valStr }}">{{ $valStr }}</span>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="bg-gray-800/50 rounded-lg px-2.5 py-1.5 border border-gray-700/50 flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-[10px] sm:text-[11px] text-amber-400/90 font-bold uppercase tracking-wider shrink-0">Durum</span>
                        <span class="text-xs text-white font-bold">{{ ($conditionBadge ?? 'Sıfır') == 'Sıfır' ? 'Garantili Sıfır' : 'Test Edilmiş 2. El' }}</span>
                    </div>
                    <div class="bg-gray-800/50 rounded-lg px-2.5 py-1.5 border border-gray-700/50 flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-[10px] sm:text-[11px] text-amber-400/90 font-bold uppercase tracking-wider shrink-0">Kargo</span>
                        <span class="text-xs text-white font-bold">Aynı Gün Hızlı Kargo</span>
                    </div>
                    <div class="bg-gray-800/50 rounded-lg px-2.5 py-1.5 border border-gray-700/50 flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-[10px] sm:text-[11px] text-amber-400/90 font-bold uppercase tracking-wider shrink-0">Belge</span>
                        <span class="text-xs text-white font-bold">Adınıza Faturalı</span>
                    </div>
                @endif
            </div>

            <button type="button" onclick="event.stopPropagation(); window.location.href='{{ route('products.show', $slug) }}'" style="display:block; width:100%; padding:0.65rem 0.75rem; background:#eab308; color:#0f172a; font-weight:800; border-radius:0.75rem; text-align:center; border:none; cursor:pointer; margin-top:0.75rem; transition:background 0.2s; flex-shrink:0;" class="hover:bg-yellow-400 text-xs sm:text-sm shadow-md">
                Detayları İncele <i class="fa-solid fa-arrow-right text-[11px] ml-1"></i>
            </button>
        </div>

    </div>
</div>
