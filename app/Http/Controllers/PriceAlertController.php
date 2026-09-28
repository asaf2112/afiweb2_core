<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'target_price' => 'required|numeric|min:1',
            'contact_info' => 'nullable|string|max:255'
        ]);

        $user_id = \Illuminate\Support\Facades\Auth::id();
        $contact_info = $request->contact_info;

        if (!$user_id && !$contact_info) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lütfen iletişim bilginizi (e-posta veya telefon) giriniz.'
            ], 400);
        }

        // Eğer daha önce kurulmuş ve bildirim gitmemişse güncelle
        $alert = \App\Models\PriceAlert::updateOrCreate(
            [
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'contact_info' => $user_id ? null : $contact_info,
                'is_notified' => false
            ],
            [
                'target_price' => $request->target_price
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Fiyat alarmı başarıyla kuruldu. Fiyat ' . number_format($request->target_price, 2) . ' ₺ veya altına düştüğünde size haber vereceğiz.'
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user_id = \Illuminate\Support\Facades\Auth::id();
        if (!$user_id) {
            return response()->json(['status' => 'error'], 401);
        }

        \App\Models\PriceAlert::where('user_id', $user_id)
            ->where('is_notified', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}
