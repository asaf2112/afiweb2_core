<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        // Tüm müşterileri (admin olmayanları) sipariş sayıları ile birlikte çekiyoruz
        $query = User::where('role', '!=', 'admin') // Eğer rol kontrolünüz farklıysa burayı uyarlayabilirsiniz.
            ->withCount('orders')
            ->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $customers = $query->paginate(15)->appends($request->all());
            
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer and their orders.
     */
    public function show($id)
    {
        $customer = User::with(['orders' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // Admin kullanıcısının silinmesini engelle
        if ($customer->role === 'admin') {
            return redirect()->route('admin.customers.index')->with('error', 'Yönetici hesapları silinemez.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Müşteri hesabı başarıyla silindi.');
    }
}
