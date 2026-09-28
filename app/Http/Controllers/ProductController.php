<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    /**
     * Ana sayfayı gösterir ve öne çıkan, popüler ve flash indirimli ürünleri getirir.
     */
    public function home()
    {
        $banners = \App\Models\Banner::active()->ordered()->get();

        $featuredProducts = Product::with('category')
            ->where(function ($q) {
                $q->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
            })
            ->latest()->take(8)->get();

        $popularProducts = Product::with('category')
            ->where(function ($q) {
                $q->whereNotNull('badge')
                  ->orWhere('stock', '>', 0);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();

        $flashSaleProducts = Product::with('category')
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->whereColumn('discount_price', '<', 'price')
            ->where(function ($q) {
                $q->whereNull('discount_end_date')
                  ->orWhere('discount_end_date', '>=', now()->subHours(12));
            })
            ->latest('updated_at')
            ->take(8)
            ->get();

        $bestsellers = Product::with('category')
            ->where(function ($q) {
                $q->where('is_bestseller', true)
                  ->orWhere('sales_count', '>', 0);
            })
            ->orderByDesc('is_bestseller')
            ->orderByDesc('sales_count')
            ->latest()
            ->take(8)
            ->get();

        if ($bestsellers->count() < 4) {
            $bestsellers = Product::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->take(8)
                ->get();
        }

        $weeklyDeals = Product::with('category')
            ->where(function ($q) {
                $q->where('is_featured', true)
                  ->orWhereNotNull('badge');
            })
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->take(8)
            ->get();

        return view('home', compact('banners', 'featuredProducts', 'popularProducts', 'flashSaleProducts', 'bestsellers', 'weeklyDeals'));
    }

    /**
     * İkinci el (used) ürünlerin özel vitrin sayfası.
     * Sadece Laptop ve Masaüstü Bilgisayar kategorilerini kapsar.
     * usage_status alanı masaüstü alt tipini (Full Set / Sadece Kasa / Sadece Monitör) tutar.
     */
    public function secondHandIndex(Request $request)
    {
        // Yalnızca Laptop (slug: laptop) ve Masaüstü (slug: masaustu-bilgisayar) kategorileri
        $allowedSlugs      = ['laptop', 'masaustu-bilgisayar'];
        $allowedCategories = Category::whereIn('slug', $allowedSlugs)->get();
        $allowedCatIds     = $allowedCategories->pluck('id')->toArray();

        $query = Product::with('category')
            ->where('condition_type', 'used')
            ->whereIn('category_id', $allowedCatIds);

        // Kategori filtresi (sadece izin verilen kategoriler içinde)
        if ($request->filled('category_id') && in_array($request->input('category_id'), $allowedCatIds)) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Masaüstü Tipi filtresi — usage_status sütunu üzerinden
        $desktopTypeFilter = $request->input('desktop_type', []);
        if (!empty($desktopTypeFilter)) {
            $query->whereIn('usage_status', (array) $desktopTypeFilter);
        }

        // Specs filtresi
        foreach ($request->input('specs', []) as $specKey => $specValues) {
            if (!empty($specValues)) {
                $query->where(function ($q) use ($specKey, $specValues) {
                    foreach ((array) $specValues as $val) {
                        $q->orWhere("specs->{$specKey}", $val)
                          ->orWhere("specs->{$specKey}", 'LIKE', '%' . $val . '%')
                          ->orWhere('specs', 'LIKE', '%' . $val . '%');
                    }
                });
            }
        }

        // Fiyat aralığı
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        $products = $query->latest()->paginate(18)->withQueryString();

        // İstatistikler — yalnızca izin verilen kategorilerdeki ikinci el ürünler
        $totalUsed = Product::where('condition_type', 'used')
            ->whereIn('category_id', $allowedCatIds)
            ->count();

        // Desktop alt-tip sayaçları — tüm izin verilen kategorilerde usage_status'a göre say
        $desktopCatId = $allowedCategories->firstWhere('slug', 'masaustu-bilgisayar')?->id;
        $desktopTypeCounts = ['Full Set' => 0, 'Sadece Kasa' => 0, 'Sadece Monitör' => 0];
        foreach (array_keys($desktopTypeCounts) as $dtype) {
            $desktopTypeCounts[$dtype] = Product::where('condition_type', 'used')
                ->whereIn('category_id', $allowedCatIds)
                ->where('usage_status', $dtype)
                ->count();
        }

        // Sidebar için sadece Laptop + Masaüstü kategorileri (accordion yok, doğrudan liste)
        $shCategories = $allowedCategories;

        $priceRange  = $this->getPriceRange(null, 'used');
        $specFilters = $request->input('specs', []);

        $activeCategory = $request->filled('category_id')
            ? $allowedCategories->firstWhere('id', $request->input('category_id'))
            : null;

        $specFilterOptions = $activeCategory
            ? $this->buildSpecFilterOptions($activeCategory->id)
            : collect();

        return view('second-hand', compact(
            'products',
            'totalUsed',
            'shCategories',
            'priceRange',
            'specFilters',
            'specFilterOptions',
            'activeCategory',
            'desktopTypeFilter',
            'desktopTypeCounts',
            'desktopCatId'
        ));
    }

    /**
     * Ürünleri listeler; kategori ve özellik (specs) bazlı filtreleme destekler.
     * Yalnızca sıfır ürünler listelenir, ikinci el ürünler hariç tutulur.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where(function ($q) {
                $q->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
            });

        // 1. Kategori filtresi (ID veya Slug üzerinden esnek eşleşme + Tüm Alt Kategorileri Kapsa)
        $activeCategory = null;
        $activeCategoryId = $request->input('category_id');
        $categorySlug = $request->input('category') ?? $request->input('slug');

        if ($activeCategoryId) {
            $activeCategory = Category::find($activeCategoryId);
        } elseif ($categorySlug) {
            $activeCategory = Category::where('slug', $categorySlug)->first();
        }

        if ($activeCategory) {
            $activeCategoryId = $activeCategory->id;
            // Ana kategori ise kendisi ve tüm alt kategorilerinin ID'lerini kapsayacak şekilde filtrelenir
            $allCategoryIds = Category::where('id', $activeCategory->id)
                ->orWhere('parent_id', $activeCategory->id)
                ->pluck('id')
                ->toArray();

            $query->whereIn('category_id', $allCategoryIds);
        }

        // 2. Kelime/Metin ile Kapsamlı Ürün Arama (Başlık, İsim, Marka, Açıklama ve İlişkili Kategori)
        if ($request->filled('search')) {
            $searchTerm = trim($request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('brand', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('slug', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // 3. Specs (özellik) bazlı filtreleme
        // URL: ?specs[Kapasite (GB)][]=512&specs[Panel Tipi][]=IPS
        $specFilters = $request->input('specs', []);
        foreach ($specFilters as $specKey => $specValues) {
            if (!empty($specValues)) {
                $query->where(function ($q) use ($specKey, $specValues) {
                    foreach ((array) $specValues as $val) {
                        $q->orWhere("specs->{$specKey}", $val)
                          ->orWhere("specs->{$specKey}", 'LIKE', '%' . $val . '%')
                          ->orWhere('specs', 'LIKE', '%' . $val . '%');
                    }
                });
            }
        }

        // 4. Fiyat aralığı filtresi
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        // 5. Oyuncu Kasası (Gaming PC) filtresi
        if ($request->boolean('is_gaming') || $request->input('gaming_only') == '1') {
            $gamingCatIds = Category::where('slug', 'LIKE', '%oyuncu%')
                ->orWhere('name', 'LIKE', '%oyuncu%')
                ->orWhere('name', 'LIKE', '%gaming%')
                ->pluck('id')
                ->toArray();

            $query->where(function ($q) use ($gamingCatIds) {
                if (!empty($gamingCatIds)) {
                    $q->whereIn('category_id', $gamingCatIds);
                }
                $q->orWhere('badge', 'LIKE', '%Gaming%')
                  ->orWhere('badge', 'LIKE', '%Oyuncu%')
                  ->orWhere('badge', 'LIKE', '%Yayıncı%')
                  ->orWhere('badge', 'LIKE', '%Streamer%')
                  ->orWhere('title', 'LIKE', '%Oyuncu%')
                  ->orWhere('title', 'LIKE', '%Gaming%')
                  ->orWhere('title', 'LIKE', '%RGB%')
                  ->orWhere('specs->is_gaming', 'Evet')
                  ->orWhere('specs->is_gaming', '1')
                  ->orWhere('specs->is_gaming', 'true');
            });
        }

        // 5. Sıralama Seçenekleri (Sorting)
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('id', 'desc');
                break;
            case 'newest':
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Sayfalama — mevcut tüm filtre parametrelerini URL'de koru
        $products = $query->paginate(15)->withQueryString();

        // Ana kategorileri alt kategorileriyle birlikte getir (sidebar için)
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // 6. Dinamik spec filtre seçenekleri oluştur (kategori seçili olsun veya olmasın)
        $specFilterOptions = $this->buildSpecFilterOptions($activeCategoryId);

        // Fiyat aralığı için min/max değerler
        $priceRange = $this->getPriceRange($activeCategoryId);

        // Oyuncu Kasaları Toplam Sayacı (Aktif kategori bağlamında)
        $gamingCountQuery = Product::where(function ($q) {
            $q->whereNull('condition_type')
              ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
        });

        if ($activeCategoryId) {
            $allCatIdsForCount = Category::where('id', $activeCategoryId)
                ->orWhere('parent_id', $activeCategoryId)
                ->pluck('id')
                ->toArray();
            $gamingCountQuery->whereIn('category_id', $allCatIdsForCount);
        }

        $gamingCatIdsAll = Category::where('slug', 'LIKE', '%oyuncu%')
            ->orWhere('name', 'LIKE', '%oyuncu%')
            ->orWhere('name', 'LIKE', '%gaming%')
            ->pluck('id')
            ->toArray();

        $gamingCount = $gamingCountQuery->where(function ($q) use ($gamingCatIdsAll) {
            if (!empty($gamingCatIdsAll)) {
                $q->whereIn('category_id', $gamingCatIdsAll);
            }
            $q->orWhere('badge', 'LIKE', '%Gaming%')
              ->orWhere('badge', 'LIKE', '%Oyuncu%')
              ->orWhere('badge', 'LIKE', '%Yayıncı%')
              ->orWhere('badge', 'LIKE', '%Streamer%')
              ->orWhere('title', 'LIKE', '%Oyuncu%')
              ->orWhere('title', 'LIKE', '%Gaming%')
              ->orWhere('title', 'LIKE', '%RGB%')
              ->orWhere('specs->is_gaming', 'Evet')
              ->orWhere('specs->is_gaming', 'yes');
        })->count();

        return view('products.index', compact(
            'products',
            'categories',
            'activeCategory',
            'specFilterOptions',
            'specFilters',
            'priceRange',
            'gamingCount'
        ));
    }

    /**
     * Belirli bir kategoriye ait ürünleri listeler (slug tabanlı route için).
     * Sadece sıfır ürünleri listeler.
     */
    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Kategori ve tüm alt kategorilerinin ID listesini al
        $allCategoryIds = Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id')
            ->toArray();

        $query = Product::with('category')
            ->whereIn('category_id', $allCategoryIds)
            ->where(function ($q) {
                $q->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
            });

        if ($request->filled('search')) {
            $searchTerm = trim($request->input('search'));
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('brand', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('slug', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Specs filtresi
        $specFilters = $request->input('specs', []);
        foreach ($specFilters as $specKey => $specValues) {
            if (!empty($specValues)) {
                $query->where(function ($q) use ($specKey, $specValues) {
                    foreach ((array) $specValues as $val) {
                        $q->orWhere("specs->{$specKey}", $val)
                          ->orWhere("specs->{$specKey}", 'LIKE', '%' . $val . '%')
                          ->orWhere('specs', 'LIKE', '%' . $val . '%');
                    }
                });
            }
        }

        // Fiyat aralığı filtresi
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        // Oyuncu Kasası filtresi
        if ($request->boolean('is_gaming') || $request->input('gaming_only') == '1') {
            $gamingCatIds = Category::where('slug', 'LIKE', '%oyuncu%')
                ->orWhere('name', 'LIKE', '%oyuncu%')
                ->orWhere('name', 'LIKE', '%gaming%')
                ->pluck('id')
                ->toArray();

            $query->where(function ($q) use ($gamingCatIds) {
                if (!empty($gamingCatIds)) {
                    $q->whereIn('category_id', $gamingCatIds);
                }
                $q->orWhere('badge', 'LIKE', '%Gaming%')
                  ->orWhere('badge', 'LIKE', '%Oyuncu%')
                  ->orWhere('badge', 'LIKE', '%Yayıncı%')
                  ->orWhere('badge', 'LIKE', '%Streamer%')
                  ->orWhere('title', 'LIKE', '%Oyuncu%')
                  ->orWhere('title', 'LIKE', '%Gaming%')
                  ->orWhere('title', 'LIKE', '%RGB%')
                  ->orWhere('specs->is_gaming', 'Evet')
                  ->orWhere('specs->is_gaming', '1')
                  ->orWhere('specs->is_gaming', 'true');
            });
        }

        // Sıralama Seçenekleri (Sorting)
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('id', 'desc');
                break;
            case 'newest':
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        $specFilterOptions = $this->buildSpecFilterOptions($category->id);
        $priceRange        = $this->getPriceRange($category->id);
        $activeCategory    = $category;

        // Oyuncu Kasaları Sayacı
        $gamingCatIdsAll = Category::where('slug', 'LIKE', '%oyuncu%')
            ->orWhere('name', 'LIKE', '%oyuncu%')
            ->orWhere('name', 'LIKE', '%gaming%')
            ->pluck('id')
            ->toArray();

        $gamingCount = Product::whereIn('category_id', $allCategoryIds)
            ->where(function ($q) {
                $q->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
            })
            ->where(function ($q) use ($gamingCatIdsAll) {
                if (!empty($gamingCatIdsAll)) {
                    $q->whereIn('category_id', $gamingCatIdsAll);
                }
                $q->orWhere('badge', 'LIKE', '%Gaming%')
                  ->orWhere('badge', 'LIKE', '%Oyuncu%')
                  ->orWhere('badge', 'LIKE', '%Yayıncı%')
                  ->orWhere('badge', 'LIKE', '%Streamer%')
                  ->orWhere('title', 'LIKE', '%Oyuncu%')
                  ->orWhere('title', 'LIKE', '%Gaming%')
                  ->orWhere('title', 'LIKE', '%RGB%')
                  ->orWhere('specs->is_gaming', 'Evet')
                  ->orWhere('specs->is_gaming', '1')
                  ->orWhere('specs->is_gaming', 'true')
                  ->orWhere('specs->is_gaming', 'yes');
            })->count();

        return view('products.index', compact(
            'products',
            'categories',
            'category',
            'activeCategory',
            'specFilterOptions',
            'specFilters',
            'priceRange',
            'gamingCount'
        ));
    }

    /**
     * Verilen kategori ID'si ve alt kategorileri için ürünlerin specs sütunundan
     * unique filtre seçeneklerini çıkarır.
     *
     * @param  int|null $categoryId
     * @return \Illuminate\Support\Collection  ['Kapasite (GB)' => ['512', '1024', ...], ...]
     */
    private function buildSpecFilterOptions(?int $categoryId): Collection
    {
        $query = Product::where(function ($q) {
            $q->whereNull('condition_type')
              ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
        })->whereNotNull('specs');

        if ($categoryId) {
            $allCategoryIds = Category::where('id', $categoryId)
                ->orWhere('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();
            $query->whereIn('category_id', $allCategoryIds);
        }

        $products = $query->get(['specs']);

        $allKeys    = collect();
        $optionsMap = [];

        foreach ($products as $product) {
            $specs = is_array($product->specs)
                ? $product->specs
                : (json_decode($product->specs, true) ?? []);

            foreach ($specs as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $displayKey = is_string($key) ? $key : (string) $key;
                $rawVal     = is_array($value) ? implode(', ', $value) : (string) $value;

                // ── Spec değerlerini sadeleştir ──────────────────────────────
                $normalizedVal = $this->normalizeSpecValue(strtolower($displayKey), $rawVal);

                // storage için SSD/HDD ayrımı: birden fazla normalized değer dönebilir
                $valuesToAdd = is_array($normalizedVal) ? $normalizedVal : [$normalizedVal];

                foreach ($valuesToAdd as $displayVal) {
                    if (!isset($optionsMap[$displayKey])) {
                        $optionsMap[$displayKey] = [];
                    }
                    if (!in_array($displayVal, $optionsMap[$displayKey])) {
                        $optionsMap[$displayKey][] = $displayVal;
                    }
                }
            }
        }

        // is_gaming anahtarını genel özellik filtrelerinden çıkar (özel oyuncu kasası filtresi mevcut)
        unset($optionsMap['is_gaming']);

        // Seçenek sayısı 1 olan (filtrelemeye değmez) anahtarları çıkar
        $filtered = collect($optionsMap)->filter(function ($values) {
            return count($values) >= 1;
        });

        // Her spec key'i için değerleri sırala
        return $filtered->map(fn($vals) => collect($vals)->unique()->sort()->values());
    }

    /**
     * Spec filtre değerlerini kullanıcı isteğine göre sadeleştirir.
     *
     * RAM    → sadece boyut + DDR tipi  (ör: "32GB DDR5 6000MHz" → "32GB DDR5")
     * GPU    → sadece model adı         (ör: "NVIDIA RTX 4070 Ti Super 16GB" → "NVIDIA RTX 4070 Ti Super")
     * Storage→ SSD/HDD ayrımı + boyut  (ör: "1TB NVMe M.2 SSD" → "1TB SSD", "500GB HDD" → "500GB HDD")
     */
    private function normalizeSpecValue(string $keyLower, string $rawVal): string|array
    {
        // ── RAM ─────────────────────────────────────────────────────────────
        // "32GB DDR5 6000MHz"  →  "32GB DDR5"
        // "16GB DDR4 3200MHz"  →  "16GB DDR4"
        // "32GB DDR5 RGB"      →  "32GB DDR5"
        if ($keyLower === 'ram') {
            if (preg_match('/(\d+\s*GB)\s+(DDR\d+)/i', $rawVal, $m)) {
                return trim($m[1]) . ' ' . strtoupper($m[2]);
            }
            return $rawVal;
        }

        // ── GPU ─────────────────────────────────────────────────────────────
        // "NVIDIA RTX 4070 Ti Super 16GB"  →  "NVIDIA RTX 4070 Ti Super"
        // "AMD Radeon RX 7900 XTX 24GB"    →  "AMD Radeon RX 7900 XTX"
        if ($keyLower === 'gpu') {
            // Sonunda bellek boyutu varsa kaldır (ör: "16GB", "8 GB")
            $cleaned = preg_replace('/\s+\d+\s*GB\s*$/i', '', trim($rawVal));
            return trim($cleaned) ?: $rawVal;
        }

        // ── Storage ─────────────────────────────────────────────────────────
        // "1TB NVMe M.2 SSD"   →  "1TB SSD"
        // "2TB Gen4 NVMe SSD"  →  "2TB SSD"
        // "500GB HDD"          →  "500GB HDD"
        // "1TB SSD + 2TB HDD"  →  ["1TB SSD", "2TB HDD"]
        if (in_array($keyLower, ['storage', 'depolama'])) {
            $parts  = [];
            $chunks = preg_split('/[,+&\/]+/', $rawVal);

            foreach ($chunks as $chunk) {
                $chunk = trim($chunk);
                if (empty($chunk)) continue;

                // Boyut çıkar (500GB, 1TB, 2TB vb.)
                preg_match('/(\d+(?:\.\d+)?\s*(?:TB|GB))/i', $chunk, $sizeMatch);
                $size = $sizeMatch[1] ?? null;

                if (!$size) {
                    $parts[] = $chunk; // boyut bulunamazsa olduğu gibi ekle
                    continue;
                }

                $size    = strtoupper(trim($size));
                $upper   = strtoupper($chunk);

                if (str_contains($upper, 'HDD')) {
                    $parts[] = $size . ' HDD';
                } else {
                    // SSD, NVMe, M.2, Flash hepsini SSD say
                    $parts[] = $size . ' SSD';
                }
            }

            return count($parts) > 1 ? $parts : ($parts[0] ?? $rawVal);
        }

        return $rawVal;
    }

    /**
     * Kategori (ve opsiyonel olarak durum) için min/max fiyat aralığını döndürür.
     */
    private function getPriceRange(?int $categoryId, ?string $condition = null): array
    {
        $query = Product::query();
        if ($categoryId) {
            $allCategoryIds = Category::where('id', $categoryId)
                ->orWhere('parent_id', $categoryId)
                ->pluck('id')
                ->toArray();

            $query->whereIn('category_id', $allCategoryIds);
        }
        if ($condition) {
            $query->where('condition_type', $condition);
        } else {
            $query->where(function ($q) {
                $q->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
            });
        }
        return [
            'min' => (int) ($query->min('price') ?? 0),
            'max' => (int) ($query->max('price') ?? 100000),
        ];
    }

    /**
     * Tekil ürün detayını gösterir.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'approvedReviews'])
            ->where('slug', $slug)
            ->firstOrFail();

        $specs = [];
        if ($product->category) {
            $specs = is_array($product->category->spec_fields)
                ? $product->category->spec_fields
                : (json_decode($product->category->spec_fields ?? '[]', true) ?? []);
        }

        $values = is_array($product->specs)
            ? $product->specs
            : (json_decode($product->specs ?? '[]', true) ?? []);

        $relatedProducts = $product->relatedProducts()->take(4)->get();

        if ($relatedProducts->count() < 4) {
            $categoryProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->where(function ($q) {
                    $q->whereNull('condition_type')
                      ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
                })
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->merge($categoryProducts);
        }

        return view('products.show', compact('product', 'specs', 'values', 'relatedProducts'));
    }

    /**
     * Canlı Arama (Live Search / AJAX) API Endpoint
     */
    public function searchApi(Request $request)
    {
        $searchTerm = trim($request->input('q', $request->input('search', '')));

        if (empty($searchTerm) || mb_strlen($searchTerm) < 2) {
            return response()->json([
                'success' => true,
                'count'   => 0,
                'results' => []
            ]);
        }

        $products = Product::with('category')
            ->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('brand', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhere('slug', 'LIKE', "%{$searchTerm}%");
                  });
            })
            ->latest()
            ->take(8)
            ->get();

        $results = $products->map(function ($product) {
            $img = $product->main_image 
                ? (str_starts_with($product->main_image, 'http') ? $product->main_image : asset($product->main_image)) 
                : 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400&q=80';

            $finalPrice = number_format($product->final_price ?? $product->price, 0, ',', '.') . ' ₺';
            $originalPrice = ($product->discount_price && $product->discount_price < $product->price) 
                ? number_format($product->price, 0, ',', '.') . ' ₺' 
                : null;

            return [
                'id'             => $product->id,
                'title'          => $product->title ?? $product->name,
                'slug'           => $product->slug,
                'url'            => route('products.show', $product->slug),
                'image'          => $img,
                'price'          => $finalPrice,
                'original_price' => $originalPrice,
                'category_name'  => $product->category->name ?? 'Genel Donanım',
                'is_used'        => in_array($product->condition_type, ['used', 'second_hand', 'ikinci_el']),
                'badge'          => $product->badge
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $results->count(),
            'results' => $results
        ]);
    }
}
