<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Yorum ekleme (POST rotası için kullanılacak metod)
    public function store(Request $request, $productId)
    {
        $validatedData = $request->validate([
            'user_name' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'comment_text' => 'required|string',
        ]);

        // Yorum doğrudan 'is_approved = true' olarak kaydedilir ve hemen yayınlanır.
        $review = new Review([
            'product_id'   => $productId,
            'user_name'    => $validatedData['user_name'],
            'rating'       => $validatedData['rating'],
            'comment_text' => $validatedData['comment_text'],
        ]);
        $review->is_approved = true;
        $review->save();

        return back()->with('success', 'Yorumunuz başarıyla gönderildi ve yayınlandı.');
    }

    // Admin paneli için bekleyen yorumları listeleme
    public function pendingReviews()
    {
        $reviews = Review::with('product')->where('is_approved', false)->latest()->get();
        return view('admin.reviews.pending', compact('reviews'));
    }

    // Admin tarafından yorum onaylama
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = true;
        $review->save();

        return back()->with('success', 'Yorum onaylandı.');
    }

    // Admin tarafından yorum silme (Reddetme)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Yorum silindi.');
    }
}
