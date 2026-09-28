<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();
        return view('customer.orders', compact('orders'));
    }

    public function show($id)
    {
        // Kullanıcının kendi siparişi olduğundan emin oluyoruz
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        return view('customer.order_details', compact('order'));
    }
}
