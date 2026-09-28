<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Siparişleri Çek
        $orders = [];
        if (class_exists(Order::class) && \Illuminate\Support\Facades\Schema::hasColumn('orders', 'user_id')) {
            $orders = Order::where('user_id', $user->id)->latest()->get();
        } else {
            $orders = collect();
        }

        // Favorileri Çek (Eğer wishlist veya product ilişkisi varsa)
        // Şimdilik boş bir koleksiyon gönderiyoruz.
        $favorites = collect(); 

        return view('profile', compact('user', 'orders', 'favorites'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [];
        $messages = [
            'phone.regex' => 'Telefon numarası geçerli bir Türkiye formatında olmalıdır (Örn: 05XX XXX XX XX)'
        ];

        if (empty($user->email) && $request->filled('email')) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->id;
        }

        if (empty($user->phone) && $request->filled('phone')) {
            $rules['phone'] = ['required', 'string', 'regex:/^05[0-9]{2}\s?[0-9]{3}\s?[0-9]{2}\s?[0-9]{2}$/', 'unique:users,phone,' . $user->id];
        }

        if (empty($rules)) {
            return back()->with('error', 'Lütfen eklemek istediğiniz bilgiyi girin.');
        }

        $validated = $request->validate($rules, $messages);
        
        $user->update($validated);

        return back()->with('success', 'Eksik bilgileriniz başarıyla eklendi.');
    }

    public function notifications()
    {
        $user = Auth::user();
        
        $notifications = \App\Models\PriceAlert::with('product')
            ->where('user_id', $user->id)
            ->where('is_notified', true)
            ->latest('updated_at')
            ->get();

        return view('customer.notifications', compact('notifications'));
    }
}
