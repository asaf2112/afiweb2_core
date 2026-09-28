<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Karşılaştırma ekranını görüntüler.
     */
    public function index()
    {
        $compareIds = session()->get('compare', []);
        $products = Product::whereIn('id', $compareIds)->with(['category', 'images'])->get();

        return view('compare.index', compact('products'));
    }

    /**
     * Karşılaştırma listesine ürün ekler veya çıkarır (Max 4 ürün, sadece aynı kategori).
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::with('category')->findOrFail($productId);
        $compareList = session()->get('compare', []);

        if (in_array($productId, $compareList)) {
            // Listeden Çıkar
            $compareList = array_values(array_diff($compareList, [$productId]));
            session()->put('compare', $compareList);

            return response()->json([
                'status' => 'removed',
                'message' => "'{$product->title}' karşılaştırma listesinden çıkarıldı.",
                'count' => count($compareList),
                'list' => $this->getCompareData($compareList)
            ]);
        } else {
            // 1. Liste Limiti Kontrolü (Max 4 ürün)
            if (count($compareList) >= 4) {
                return response()->json([
                    'status' => 'limit_reached',
                    'message' => 'En fazla 4 ürünü aynı anda karşılaştırabilirsiniz.',
                    'count' => count($compareList),
                    'list' => $this->getCompareData($compareList)
                ], 422);
            }

            // 2. Kategori Kısıtlaması Kontrolü (Sadece Aynı Kategori)
            if (!empty($compareList)) {
                $existingProducts = Product::whereIn('id', $compareList)->with('category')->get();
                $firstCat = $existingProducts->first()?->category;

                if ($firstCat && $product->category_id !== $firstCat->id) {
                    $catName = $firstCat->name;
                    return response()->json([
                        'status' => 'category_mismatch',
                        'message' => "Karşılaştırma listesine sadece aynı kategorideki ürünleri ekleyebilirsiniz! Mevcut Kategori: '{$catName}'",
                        'current_category' => $catName,
                        'count' => count($compareList),
                        'list' => $this->getCompareData($compareList)
                    ], 422);
                }
            }

            $compareList[] = (int)$productId;
            session()->put('compare', array_unique($compareList));

            return response()->json([
                'status' => 'added',
                'message' => "'{$product->title}' karşılaştırma listesine eklendi!",
                'count' => count($compareList),
                'list' => $this->getCompareData($compareList)
            ]);
        }
    }

    /**
     * Karşılaştırma listesini tamamen temizler.
     */
    public function clear()
    {
        session()->forget('compare');

        return response()->json([
            'status' => 'cleared',
            'message' => 'Karşılaştırma listesi temizlendi.',
            'count' => 0,
            'list' => []
        ]);
    }

    /**
     * Floating bar için JSON listesi sunar.
     */
    public function apiList()
    {
        $compareIds = session()->get('compare', []);
        return response()->json([
            'count' => count($compareIds),
            'list' => $this->getCompareData($compareIds)
        ]);
    }

    /**
     * Yardımcı: Seçili ürünlerin temel verilerini döndürür.
     */
    private function getCompareData(array $compareIds)
    {
        if (empty($compareIds)) return [];

        $products = Product::whereIn('id', $compareIds)->get();
        return $products->map(function($p) {
            $imageUrl = 'https://placehold.co/100x100/1e293b/94a3b8?text=Gorsel+Yok';
            if ($p->main_image) {
                if (file_exists(public_path($p->main_image))) {
                    $imageUrl = asset($p->main_image);
                } elseif (file_exists(public_path('storage/' . $p->main_image))) {
                    $imageUrl = asset('storage/' . $p->main_image);
                }
            }

            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'category_id' => $p->category_id,
                'category_name' => $p->category->name ?? 'Genel',
                'price' => number_format($p->final_price, 2, ',', '.') . ' ₺',
                'image' => $imageUrl
            ];
        });
    }
}
