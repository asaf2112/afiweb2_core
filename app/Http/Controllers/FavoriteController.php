<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Favori ürünler sayfasını görüntüler.
     */
    public function index()
    {
        if (Auth::check()) {
            $favorites = Auth::user()->favoriteProducts()->with('images')->latest('favorites.created_at')->get();
        } else {
            $favIds = session()->get('favorites', []);
            $favorites = Product::whereIn('id', $favIds)->with('images')->get();
        }

        return view('customer.favorites', compact('favorites'));
    }

    /**
     * Ürünün favori durumunu değiştirir (Auth + Guest hibrit desteği).
     */
    public function toggle(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            $user = Auth::user();
            $isFavorited = $user->favorites()->where('product_id', $productId)->exists();

            if ($isFavorited) {
                $user->favorites()->where('product_id', $productId)->delete();
                $favCount = $user->favorites()->count();
                return response()->json([
                    'status' => 'removed',
                    'message' => "'{$product->title}' favorilerinizden çıkarıldı.",
                    'favCount' => $favCount,
                    'isFavorited' => false
                ]);
            } else {
                $user->favorites()->create(['product_id' => $productId]);
                $favCount = $user->favorites()->count();
                return response()->json([
                    'status' => 'added',
                    'message' => "'{$product->title}' favorilerinize eklendi!",
                    'favCount' => $favCount,
                    'isFavorited' => true
                ]);
            }
        } else {
            // Misafir (Guest) Kullanıcı - Session Esaslı
            $favorites = session()->get('favorites', []);
            
            if (in_array($productId, $favorites)) {
                $favorites = array_values(array_diff($favorites, [$productId]));
                session()->put('favorites', $favorites);
                return response()->json([
                    'status' => 'removed',
                    'message' => "'{$product->title}' favorilerinizden çıkarıldı.",
                    'favCount' => count($favorites),
                    'isFavorited' => false
                ]);
            } else {
                $favorites[] = (int)$productId;
                session()->put('favorites', array_unique($favorites));
                return response()->json([
                    'status' => 'added',
                    'message' => "'{$product->title}' favorilerinize eklendi!",
                    'favCount' => count($favorites),
                    'isFavorited' => true
                ]);
            }
        }
    }

    /**
     * Anlık favori sayısını döner.
     */
    public function count()
    {
        if (Auth::check()) {
            $count = Auth::user()->favorites()->count();
        } else {
            $count = count(session()->get('favorites', []));
        }

        return response()->json(['favCount' => $count]);
    }
}
