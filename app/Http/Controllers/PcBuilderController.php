<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PcBuilderController extends Controller
{
    /**
     * PC Toplama Sihirbazı Ana Sayfası
     */
    public function index()
    {
        $categories = Category::all();
        
        return view('pc_builder.index', compact('categories'));
    }

    /**
     * Kategoriye ve uyumluluk filtrelerine göre ürünleri AJAX ile getirir.
     */
    public function getParts(Request $request)
    {
        $categorySlug = strtolower(trim($request->input('category_slug', '')));
        $search       = $request->input('search');

        // Parse selected parts from frontend
        $selectedPartsRaw = $request->input('selected_parts');
        if (is_string($selectedPartsRaw)) {
            $selectedPartsRaw = json_decode($selectedPartsRaw, true) ?: [];
        }
        if (!is_array($selectedPartsRaw)) {
            $selectedPartsRaw = [];
        }

        $cpuId = is_array($selectedPartsRaw['cpu'] ?? null) ? ($selectedPartsRaw['cpu']['id'] ?? null) : ($selectedPartsRaw['cpu'] ?? null);
        $mbId  = is_array($selectedPartsRaw['motherboard'] ?? null) ? ($selectedPartsRaw['motherboard']['id'] ?? null) : ($selectedPartsRaw['motherboard'] ?? null);
        $ramId = is_array($selectedPartsRaw['ram'] ?? null) ? ($selectedPartsRaw['ram']['id'] ?? null) : ($selectedPartsRaw['ram'] ?? null);
        $gpuId = is_array($selectedPartsRaw['gpu'] ?? null) ? ($selectedPartsRaw['gpu']['id'] ?? null) : ($selectedPartsRaw['gpu'] ?? null);
        $caseId= is_array($selectedPartsRaw['case'] ?? null) ? ($selectedPartsRaw['case']['id'] ?? null) : ($selectedPartsRaw['case'] ?? null);

        $selectedCpu   = !empty($cpuId) ? Product::find($cpuId) : null;
        $selectedMb    = !empty($mbId) ? Product::find($mbId) : null;
        $selectedRam   = !empty($ramId) ? Product::find($ramId) : null;
        $selectedGpu   = !empty($gpuId) ? Product::find($gpuId) : null;
        $selectedCase  = !empty($caseId) ? Product::find($caseId) : null;

        // 1. Soket Kısıtı (İşlemci veya Anakart tarafından belirlenir)
        $activeSocket = null;
        if ($selectedCpu && $selectedCpu->effective_socket) {
            $activeSocket = $selectedCpu->effective_socket;
        } elseif ($selectedMb && $selectedMb->effective_socket) {
            $activeSocket = $selectedMb->effective_socket;
        } elseif (!empty($request->input('cpu_socket'))) {
            $activeSocket = strtoupper(str_replace([' ', '-', '_'], '', trim($request->input('cpu_socket'))));
        }

        // 2. Platform Kısıtı (INTEL veya AMD)
        $activePlatform = null;
        if ($selectedCpu && $selectedCpu->effective_platform) {
            $activePlatform = $selectedCpu->effective_platform;
        } elseif ($selectedMb && $selectedMb->effective_platform) {
            $activePlatform = $selectedMb->effective_platform;
        }

        // 3. RAM Tipi Kısıtı (DDR4 veya DDR5)
        $activeRamType = null;
        if ($selectedMb && $selectedMb->effective_ram_type) {
            $activeRamType = $selectedMb->effective_ram_type;
        } elseif ($selectedRam && $selectedRam->effective_ram_type) {
            $activeRamType = $selectedRam->effective_ram_type;
        } elseif (!empty($request->input('ram_type'))) {
            $activeRamType = strtoupper(trim($request->input('ram_type')));
        }

        // 4. Sistem TDP & Güç Kaynağı (PSU) Minimum Watt Hesaplaması
        $totalSystemTdp = 0;
        if ($selectedCpu) {
            $totalSystemTdp += ($selectedCpu->effective_tdp_watt > 0 ? $selectedCpu->effective_tdp_watt : 65);
        }
        if ($selectedGpu) {
            $totalSystemTdp += ($selectedGpu->effective_tdp_watt > 0 ? $selectedGpu->effective_tdp_watt : 200);
        }
        if ($selectedRam) {
            $totalSystemTdp += 10;
        }
        if ($selectedCpu || $selectedGpu || $selectedMb) {
            $totalSystemTdp += 100; // Anakart, fanlar, SSD/HDD taban güç tüketimi
        }
        $minRequiredPsuWatt = $totalSystemTdp > 0 ? (int) ceil($totalSystemTdp * 1.15) : 0;

        $query = Product::query()->with('category')
            ->whereDoesntHave('category', function ($q) {
                $q->whereIn('slug', [
                    'oyuncu-kasalari', 'standart-masaustu-kasalar', 'hazir-sistemler',
                    'masaustu-bilgisayar', 'laptop', 'monitor', 'kamera-guvenlik',
                    'mouse', 'klavye', 'kulaklik', 'mousepad', 'ag-ve-modem'
                ])
                ->orWhere('name', 'LIKE', '%Hazır Sistem%')
                ->orWhere('name', 'LIKE', '%Masaüstü Bilgisayar%')
                ->orWhere('name', 'LIKE', '%Oyuncu Kasaları%')
                ->orWhere('name', 'LIKE', '%Laptop%');
            })
            ->where(function ($q) {
                $q->where('title', 'NOT LIKE', '%Masaüstü Bilgisayar%')
                  ->where('title', 'NOT LIKE', '%Oyuncu Kasası%')
                  ->where('title', 'NOT LIKE', '%Hazır Sistem%')
                  ->where('title', 'NOT LIKE', '%Gaming PC%')
                  ->where('title', 'NOT LIKE', '%Toplama PC%');
            });

        // Esnek Kategori Eşleşmesi (SSD/HDD, CPU, RAM vb. alt kategoriler için)
        if ($categorySlug) {
            $slugClean = $categorySlug;
            $query->where(function ($topQuery) use ($slugClean) {
                $topQuery->whereHas('category', function ($q) use ($slugClean) {
                    if (in_array($slugClean, ['ssd-hdd', 'storage', 'depolama'])) {
                        $q->whereIn('slug', ['ssd', 'ssd-depolama', 'hdd', 'depolama'])
                          ->orWhere('name', 'LIKE', '%ssd%')
                          ->orWhere('name', 'LIKE', '%hdd%')
                          ->orWhere('name', 'LIKE', '%depolama%');
                    } elseif (in_array($slugClean, ['islemci', 'cpu'])) {
                        $q->where('slug', 'LIKE', '%islemci%')
                          ->orWhere('slug', 'LIKE', '%cpu%')
                          ->orWhere('name', 'LIKE', '%işlemci%')
                          ->orWhere('name', 'LIKE', '%islemci%');
                    } elseif (in_array($slugClean, ['anakart', 'motherboard'])) {
                        $q->where('slug', 'LIKE', '%anakart%')
                          ->orWhere('slug', 'LIKE', '%motherboard%')
                          ->orWhere('name', 'LIKE', '%anakart%');
                    } elseif (in_array($slugClean, ['ram', 'bellek', 'memory'])) {
                        $q->where('slug', 'LIKE', '%ram%')
                          ->orWhere('slug', 'LIKE', '%bellek%')
                          ->orWhere('name', 'LIKE', '%ram%')
                          ->orWhere('name', 'LIKE', '%bellek%');
                    } elseif (in_array($slugClean, ['ekran-karti', 'gpu', 'vga'])) {
                        $q->where('slug', 'LIKE', '%ekran-karti%')
                          ->orWhere('slug', 'LIKE', '%gpu%')
                          ->orWhere('slug', 'LIKE', '%vga%')
                          ->orWhere('name', 'LIKE', '%ekran kartı%');
                    } elseif (in_array($slugClean, ['guc-kaynagi', 'psu'])) {
                        $q->where('slug', 'LIKE', '%guc-kaynagi%')
                          ->orWhere('slug', 'LIKE', '%psu%')
                          ->orWhere('slug', 'LIKE', '%power%')
                          ->orWhere('name', 'LIKE', '%güç kaynağı%')
                          ->orWhere('name', 'LIKE', '%guc kaynagi%');
                    } elseif ($slugClean === 'ssd') {
                        $q->whereIn('slug', ['ssd', 'ssd-depolama'])
                          ->orWhere('name', 'LIKE', '%ssd%');
                    } elseif ($slugClean === 'hdd') {
                        $q->where('slug', 'hdd')
                          ->orWhere('name', 'LIKE', '%hdd%')
                          ->orWhere('name', 'LIKE', '%harddisk%');
                    } elseif (in_array($slugClean, ['kasa', 'case', 'bilgisayar-kasasi'])) {
                        $q->where('slug', 'bilgisayar-kasasi')
                          ->orWhere('name', 'LIKE', '%bilgisayar kasası%');
                    } else {
                        $q->where('slug', 'LIKE', "%{$slugClean}%")
                          ->orWhere('name', 'LIKE', "%{$slugClean}%");
                    }
                });
            });
        }

        // Arama filtresi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        $allProducts = $query->latest()->get();

        // Esnek Fallback: Eğer kategori sorgusundan ürün çıkmadıysa ilgili bileşen kategorilerinden getir
        if ($allProducts->isEmpty() && $categorySlug === 'ssd') {
            $allProducts = Product::whereHas('category', function($q) {
                $q->whereIn('slug', ['ssd', 'ssd-depolama'])->orWhere('name', 'LIKE', '%ssd%');
            })->latest()->get();
        } elseif ($allProducts->isEmpty() && $categorySlug === 'hdd') {
            $allProducts = Product::whereHas('category', function($q) {
                $q->where('slug', 'hdd')->orWhere('name', 'LIKE', '%hdd%');
            })->latest()->get();
        } elseif ($allProducts->isEmpty() && in_array($categorySlug, ['ssd-hdd', 'storage', 'depolama'])) {
            $allProducts = Product::whereHas('category', function($q) {
                $q->whereIn('slug', ['ssd', 'ssd-depolama', 'hdd'])->orWhere('name', 'LIKE', '%depolama%');
            })->latest()->get();
        } elseif ($allProducts->isEmpty() && in_array($categorySlug, ['kasa', 'case', 'bilgisayar-kasasi'])) {
            $allProducts = Product::whereHas('category', function($q) {
                $q->where('slug', 'bilgisayar-kasasi');
            })->latest()->get();
        }

        // Akıllı Donanım Filtreleme (Soket, Platform, RAM Tipi, Güç Kaynağı Watt, GPU Boyut)
        $filtered = $allProducts->filter(function ($p) use (
            $categorySlug, $activeSocket, $activePlatform, $activeRamType, $minRequiredPsuWatt,
            $selectedGpu, $selectedCase
        ) {
            $titleUpper = strtoupper($p->title ?? '');
            $catSlug    = strtolower($p->category->slug ?? '');
            $slug       = $catSlug;
            $catName    = strtoupper($p->category->name ?? '');

            // 0. Kesin Kural: Hazır Sistemler, Komple Masaüstü Bilgisayarlar ve Oyuncu Kasaları Parça Listesinde YER ALAMAZ
            if (
                in_array($catSlug, ['oyuncu-kasalari', 'standart-masaustu-kasalar', 'hazir-sistemler', 'masaustu-bilgisayar', 'laptop']) ||
                str_contains($catSlug, 'hazir-sistem') || str_contains($catSlug, 'gaming-pc') || str_contains($catSlug, 'masaustu') ||
                str_contains($catName, 'HAZIR SİSTEM') || str_contains($catName, 'MASAÜSTÜ BİLGİSAYAR') || str_contains($catName, 'OYUNCU KASALARI') ||
                str_contains($titleUpper, 'MASAÜSTÜ BİLGİSAYAR') || str_contains($titleUpper, 'OYUNCU KASASI') || str_contains($titleUpper, 'HAZIR SİSTEM') ||
                str_contains($titleUpper, 'GAMING PC') || str_contains($titleUpper, 'TOPLAMA PC') ||
                (str_contains($titleUpper, 'RTX') && str_contains($titleUpper, 'RAM') && (str_contains($titleUpper, 'SSD') || str_contains($titleUpper, 'NVME')))
            ) {
                return false;
            }

            // 1. SSD & HDD Kesin İzolasyon Filtresi
            if ($categorySlug === 'ssd') {
                if ($catSlug === 'hdd' || (str_contains($titleUpper, 'HDD') && !str_contains($titleUpper, 'SSD')) || str_contains($titleUpper, 'MEKANİK') || str_contains($titleUpper, 'HARDDİSK')) {
                    return false;
                }
            } elseif ($categorySlug === 'hdd') {
                if ($catSlug === 'ssd' || str_contains($titleUpper, 'SSD') || str_contains($titleUpper, 'NVME') || str_contains($titleUpper, 'M.2')) {
                    return false;
                }
            }

            // 2. Anakart (Motherboard) Uyumluluk Filtresi
            if (in_array($categorySlug, ['anakart', 'motherboard']) || str_contains($slug, 'anakart') || str_contains($slug, 'motherboard')) {
                if ($activeSocket) {
                    $pSocket = $p->effective_socket;
                    if (!$pSocket || $pSocket !== $activeSocket) {
                        return false;
                    }
                }
                if ($activePlatform) {
                    $pPlatform = $p->effective_platform;
                    if ($pPlatform && $pPlatform !== $activePlatform) {
                        return false;
                    }
                }
                if ($activeRamType) {
                    $pRamType = $p->effective_ram_type;
                    if ($pRamType && $pRamType !== $activeRamType) {
                        return false;
                    }
                }
            }

            // 3. İşlemci (CPU) Uyumluluk Filtresi
            if (in_array($categorySlug, ['islemci', 'cpu']) || str_contains($slug, 'islemci') || str_contains($slug, 'cpu')) {
                if ($activeSocket) {
                    $pSocket = $p->effective_socket;
                    if (!$pSocket || $pSocket !== $activeSocket) {
                        return false;
                    }
                }
                if ($activePlatform) {
                    $pPlatform = $p->effective_platform;
                    if ($pPlatform && $pPlatform !== $activePlatform) {
                        return false;
                    }
                }
            }

            // 4. Bellek (RAM) Uyumluluk Filtresi (DDR4 / DDR5)
            if (in_array($categorySlug, ['ram', 'bellek', 'memory']) || str_contains($slug, 'ram') || str_contains($slug, 'bellek')) {
                if ($activeRamType) {
                    $pRamType = $p->effective_ram_type;
                    if ($pRamType && $pRamType !== $activeRamType) {
                        return false;
                    }
                }
            }

            // 5. Güç Kaynağı (PSU) Uyumluluk Filtresi
            // KULLANICI İSTEĞİ: "düşük güçe sahip power seçilmesin seçilen parçalara göre veya daha iyi power suppleyler listelensin"
            if (in_array($categorySlug, ['guc-kaynagi', 'psu']) || str_contains($slug, 'guc-kaynagi') || str_contains($slug, 'psu')) {
                if ($minRequiredPsuWatt > 0) {
                    $pWatt = $p->effective_tdp_watt;
                    if ($pWatt > 0 && $pWatt < $minRequiredPsuWatt) {
                        return false;
                    }
                }
            }

            // 6. Boş Kasa Filtresi (Hazır Sistemleri ele ve GPU uzunluğunu kontrol et)
            if (in_array($categorySlug, ['kasa', 'case', 'bilgisayar-kasasi'])) {
                $titleUpper = strtoupper($p->title ?? '');
                $catSlug    = strtolower($p->category->slug ?? '');
                $catName    = strtoupper($p->category->name ?? '');

                // Hazır Sistem kategorisindeki ürünleri ele
                if (str_contains($catSlug, 'hazir-sistem') || str_contains($catSlug, 'gaming-pc') || str_contains($catSlug, 'toplama') || str_contains($catName, 'HAZIR SİSTEM') || str_contains($catName, 'MASAÜSTÜ BİLGİSAYAR')) {
                    return false;
                }

                // Başlıkta tam sistem / işlemci / RAM / ekran kartı kombinasyonu geçen hazır PC'leri ele
                if ((str_contains($titleUpper, 'HAZIR SİSTEM') || str_contains($titleUpper, 'GAMING PC') || str_contains($titleUpper, 'TOPLAMA PC')) || (str_contains($titleUpper, 'RTX') && str_contains($titleUpper, 'GB') && (str_contains($titleUpper, 'RAM') || str_contains($titleUpper, 'SSD')))) {
                    return false;
                }

                // GPU Uzunluk Kontrolü
                if ($selectedGpu && $selectedGpu->effective_gpu_length > 0) {
                    $caseMaxGpu = $p->effective_max_gpu_length;
                    if ($caseMaxGpu > 0 && $caseMaxGpu < $selectedGpu->effective_gpu_length) {
                        return false;
                    }
                }
            }

            // 7. Ekran Kartı (GPU) Kasa Sığma Filtresi
            if (in_array($categorySlug, ['ekran-karti', 'gpu', 'vga']) || str_contains($slug, 'ekran-karti') || str_contains($slug, 'gpu')) {
                if ($selectedCase && $selectedCase->effective_max_gpu_length > 0) {
                    $gpuLen = $p->effective_gpu_length;
                    if ($gpuLen > 0 && $gpuLen > $selectedCase->effective_max_gpu_length) {
                        return false;
                    }
                }
            }

            return true;
        });

        $products = $filtered->map(function ($p) {
            $image = null;
            if (!empty($p->main_image)) {
                $image = \Illuminate\Support\Str::startsWith($p->main_image, ['http://', 'https://'])
                    ? $p->main_image
                    : asset($p->main_image);
            }

            return [
                'id'             => $p->id,
                'title'          => $p->title ?? $p->name,
                'brand'          => $p->brand ?? '',
                'stock'          => (int) $p->stock,
                'price'          => (float) $p->final_price,
                'price_formatted'=> number_format($p->final_price, 2, ',', '.') . ' TL',
                'image'          => $image,
                'socket'         => $p->effective_socket,
                'platform'       => $p->effective_platform,
                'ram_type'       => $p->effective_ram_type,
                'tdp_watt'       => $p->effective_tdp_watt,
                'category_slug'  => $p->category->slug ?? '',
            ];
        })->values();

        return response()->json([
            'success'               => true,
            'products'              => $products,
            'active_socket'         => $activeSocket,
            'active_platform'       => $activePlatform,
            'active_ram_type'       => $activeRamType,
            'min_required_psu_watt' => $minRequiredPsuWatt,
            'total_tdp'             => $totalSystemTdp,
        ]);
    }

    /**
     * Seçilen tüm parçaların uyumluluğunu kontrol eder ve sonuç verir.
     */
    /**
     * Seçilen ürünün miktarını (quantity) haritalama mantığıyla çözer.
     */
    private function resolveProductQuantity($product, $partsInput, $quantities)
    {
        $productId = $product->id;

        // 1. Direct match by Product ID in quantities map (e.g. quantities[45] = 2)
        if (isset($quantities[$productId])) {
            return max(1, (int)$quantities[$productId]);
        }

        // 2. Direct match by Slot Key in parts map (e.g. partsInput['ram'] == 45 -> check quantities['ram'])
        if (is_array($partsInput)) {
            foreach ($partsInput as $slotKey => $pId) {
                if ((int)$pId === (int)$productId && isset($quantities[$slotKey])) {
                    return max(1, (int)$quantities[$slotKey]);
                }
            }
        }

        // 3. Fallback matching category slug against slot names
        $categorySlug = strtolower($product->category->slug ?? '');
        foreach ($quantities as $key => $val) {
            if (empty($val)) continue;
            $k = strtolower($key);
            if ($k === 'cpu' && (str_contains($categorySlug, 'islemci') || str_contains($categorySlug, 'cpu'))) {
                return max(1, (int)$val);
            }
            if ($k === 'motherboard' && (str_contains($categorySlug, 'anakart') || str_contains($categorySlug, 'motherboard'))) {
                return max(1, (int)$val);
            }
            if ($k === 'ram' && (str_contains($categorySlug, 'ram') || str_contains($categorySlug, 'bellek'))) {
                return max(1, (int)$val);
            }
            if ($k === 'gpu' && (str_contains($categorySlug, 'ekran-karti') || str_contains($categorySlug, 'gpu'))) {
                return max(1, (int)$val);
            }
            if ($k === 'psu' && (str_contains($categorySlug, 'psu') || str_contains($categorySlug, 'guc-kaynagi'))) {
                return max(1, (int)$val);
            }
            if ($k === 'ssd' && str_contains($categorySlug, 'ssd')) {
                return max(1, (int)$val);
            }
            if ($k === 'hdd' && str_contains($categorySlug, 'hdd')) {
                return max(1, (int)$val);
            }
            if ($k === 'case' && (str_contains($categorySlug, 'kasa') || str_contains($categorySlug, 'case'))) {
                return max(1, (int)$val);
            }
        }

        return 1;
    }

    public function validateConfig(Request $request)
    {
        $partsInput  = $request->input('parts', $request->json('parts', []));
        $selectedIds = is_array($partsInput) ? array_filter(array_values($partsInput)) : [];
        $quantities  = $request->input('quantities', $request->json('quantities', []));

        $products    = Product::whereIn('id', $selectedIds)->get();

        $errors       = [];
        $warnings     = [];
        $totalTdp     = 0;
        $minPsuWatt   = 0;
        $totalPrice   = 0;

        $cpu        = null;
        $motherboard= null;
        $ram        = null;
        $gpu        = null;
        $psu        = null;
        $ssd        = null;
        $hdd        = null;
        $case       = null;

        $productQtyMap = [];
        foreach ($products as $p) {
            $slug = strtolower($p->category->slug ?? '');
            
            $qty = $this->resolveProductQuantity($p, $partsInput, $quantities);
            $productQtyMap[$p->id] = $qty;
            $totalPrice += ($p->final_price * $qty);

            if (str_contains($slug, 'islemci') || str_contains($slug, 'cpu')) {
                $cpu = $p;
                $totalTdp += ($p->effective_tdp_watt > 0 ? $p->effective_tdp_watt : 65) * $qty;
            } elseif (str_contains($slug, 'anakart') || str_contains($slug, 'motherboard')) {
                $motherboard = $p;
            } elseif (str_contains($slug, 'ram') || str_contains($slug, 'bellek')) {
                $ram = $p;
                $totalTdp += 10 * $qty;
            } elseif (str_contains($slug, 'ekran-karti') || str_contains($slug, 'gpu')) {
                $gpu = $p;
                $totalTdp += ($p->effective_tdp_watt > 0 ? $p->effective_tdp_watt : 200) * $qty;
            } elseif (str_contains($slug, 'guc-kaynagi') || str_contains($slug, 'psu')) {
                $psu = $p;
            } elseif ($slug === 'ssd') {
                $ssd = $p;
                $totalTdp += 5 * $qty;
            } elseif ($slug === 'hdd') {
                $hdd = $p;
                $totalTdp += 8 * $qty;
            } elseif (str_contains($slug, 'kasa') || str_contains($slug, 'case')) {
                $case = $p;
            }
        }

        // Sistem Taban Güç Tüketimi (Anakart, Fanlar vb.) ~ 100W
        $systemBaseWatt = 100;
        $calculatedTotalTdp = $totalTdp + $systemBaseWatt;
        $minPsuWatt = (int) ceil($calculatedTotalTdp * 1.25);

        // 1. Marka ve Soket Uyumluluğu Kontrolü
        if ($cpu && $motherboard) {
            $cpuPlatform  = $cpu->effective_platform;
            $mbPlatform   = $motherboard->effective_platform;
            $cpuSocket    = $cpu->effective_socket;
            $mbSocket     = $motherboard->effective_socket;

            if ($cpuPlatform && $mbPlatform && $cpuPlatform !== $mbPlatform) {
                $errors[] = "Marka Mimarisi Uyumsuzluğu: Seçilen {$cpuPlatform} işlemci ile {$mbPlatform} anakart birbiriyle fiziki ve elektriksel olarak çalışamaz!";
            } elseif ($cpuSocket && $mbSocket && strtolower($cpuSocket) !== strtolower($mbSocket)) {
                $errors[] = "Soket Uyumsuzluğu: Seçilen İşlemci soketi ({$cpuSocket}) ile Anakart soket yuvası ({$mbSocket}) birbiriyle uyumsuzdur!";
            }
        }

        // 2. RAM Uyumluluğu Kontrolü (DDR4 vs DDR5)
        if ($motherboard && $ram) {
            $mbRamType  = $motherboard->effective_ram_type;
            $ramRamType = $ram->effective_ram_type;

            if ($mbRamType && $ramRamType && strtolower($mbRamType) !== strtolower($ramRamType)) {
                $errors[] = "Bellek (RAM) Uyumsuzluğu: Seçtiğiniz Anakart ({$mbRamType}) bellek mimarisini desteklerken, seçtiğiniz RAM ({$ramRamType}) tipindedir!";
            }
        }

        // 3. Anakart Yuva Kontrolü (RAM ve M.2 SSD Slot Sınırı)
        if ($motherboard) {
            $maxRamSlots = $motherboard->effective_ram_slots; // 2 veya 4
            $maxM2Slots  = $motherboard->effective_m2_slots;  // 2 veya 4

            if ($ram) {
                $ramQty = $productQtyMap[$ram->id] ?? 1;
                if ($ramQty > $maxRamSlots) {
                    $errors[] = "Anakart RAM Yuva Aşımı: Seçtiğiniz {$motherboard->title} anakart maksimum {$maxRamSlots} adet RAM slotuna sahiptir, ancak siz {$ramQty} adet RAM seçtiniz!";
                }
            }

            if ($ssd) {
                $ssdQty = $productQtyMap[$ssd->id] ?? 1;
                if ($ssdQty > $maxM2Slots) {
                    $warnings[] = "Anakart M.2 Yuva Uyarısı: Seçtiğiniz anakart üzerinde {$maxM2Slots} adet M.2 SSD yuvası bulunmaktadır. {$ssdQty} adet M.2 SSD takmak için ek yuva gerekebilir.";
                }
            }
        }

        // 4. PSU Güç Kaynağı Kontrolü
        if ($psu) {
            $psuWattage = $psu->effective_tdp_watt;
            if ($psuWattage > 0 && $psuWattage < $minPsuWatt) {
                $errors[] = "Yetersiz Güç Kaynağı (PSU): Seçilen sistem kararlı çalışmak için en az {$minPsuWatt}W PSU gerektirirken, seçtiğiniz PSU {$psuWattage}W gücündedir!";
            }
        } elseif ($totalTdp > 0) {
            $warnings[] = "Bu konfigürasyon için en az {$minPsuWatt}W gücünde bir Güç Kaynağı (PSU) seçmeniz tavsiye edilir.";
        }

        // 5. Kasa & Ekran Kartı Uzunluğu Kontrolü
        if ($case && $gpu) {
            $caseMaxGpu = $case->effective_max_gpu_length;
            $gpuLength  = $gpu->effective_gpu_length;

            if ($gpuLength > 0 && $caseMaxGpu > 0 && $gpuLength > $caseMaxGpu) {
                $errors[] = "Fiziksel Boyut Uyumsuzluğu: Seçtiğiniz Ekran Kartı uzunluğu ({$gpuLength}mm), kasanın desteklediği maksimum alan sınırından ({$caseMaxGpu}mm) uzundur!";
            }
        }

        $isCompatible = empty($errors);

        return response()->json([
            'success'        => $isCompatible,
            'is_compatible'  => $isCompatible,
            'errors'         => $errors,
            'warnings'       => $warnings,
            'total_price'    => number_format($totalPrice, 2, ',', '.') . ' TL',
            'raw_total_price'=> $totalPrice,
            'total_tdp'      => $totalTdp,
            'min_psu_watt'   => $minPsuWatt,
            'selected_cpu_socket' => $cpu ? $cpu->effective_socket : null,
            'selected_mb_ram_type'=> $motherboard ? $motherboard->effective_ram_type : null,
        ]);
    }

    /**
     * Toplanan tüm uyumlu konfigürasyonu topluca sepete ekler.
     * Uyumsuz parça kombinasyonlarında sepete ekleme engellenir.
     */
    public function addToCart(Request $request)
    {
        $partsInput  = $request->input('parts', $request->json('parts', []));
        $selectedIds = is_array($partsInput) ? array_filter(array_values($partsInput)) : [];

        if (empty($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Lütfen sepete eklemek için en az bir donanım parçası seçiniz.'
            ], 422);
        }

        // Uyumluluk doğrulamasını çalıştır
        $validationResponse = $this->validateConfig($request);
        $validationData = json_decode($validationResponse->getContent(), true);

        if (!$validationData['is_compatible']) {
            return response()->json([
                'success' => false,
                'message' => 'Sisteminizde uyumsuz parçalar bulunmaktadır! Lütfen hataları düzelttikten sonra sepete ekleyin.',
                'errors'  => $validationData['errors']
            ], 422);
        }

        // Sepete ekle
        $cart = session()->get('cart', []);
        $quantities = $request->input('quantities', $request->json('quantities', []));
        $addedCount = 0;

        $products = Product::whereIn('id', $selectedIds)->get();

        foreach ($products as $product) {
            $qty = $this->resolveProductQuantity($product, $partsInput, $quantities);

            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $qty;
            } else {
                $cart[$product->id] = [
                    'name'     => $product->title ?? $product->name,
                    'slug'     => $product->slug ?? '',
                    'quantity' => $qty,
                    'price'    => $product->final_price,
                    'image'    => $product->main_image
                ];
            }
            $addedCount += $qty;
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'   => true,
            'message'   => "Tebrikler! Seçtiğiniz {$addedCount} adet uyumlu donanım parçası sepete eklendi.",
            'cartCount' => collect($cart)->sum('quantity')
        ]);
    }
}
