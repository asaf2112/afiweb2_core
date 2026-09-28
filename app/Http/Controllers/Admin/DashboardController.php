<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();

        // İkinci el ürünleri hariç tutan filtre (Sadece sıfır ürünler)
        $nonSecondHandFilter = function ($query) {
            $query->whereNull('condition_type')
                  ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
        };

        // 1. Hızlı Aksiyon Kartları
        $totalOrders = \App\Models\Order::count();
        $pendingAlerts = \App\Models\PriceAlert::where('is_notified', false)->count();
        $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->where($nonSecondHandFilter)->count();
        $dailyRevenue = \App\Models\Order::whereDate('created_at', $today)
                            ->whereNotIn('status', ['İptal', 'İade'])
                            ->sum('total_amount');

        // 2. Görsel Analitik Verileri

        // Günlük Satış Trendi (Son 7 Gün)
        $last7Days = collect();
        $salesTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $last7Days->push($date->format('d M'));
            $salesTrend->push(
                \App\Models\Order::whereDate('created_at', $date)
                    ->whereNotIn('status', ['İptal', 'İade'])
                    ->sum('total_amount')
            );
        }

        // En Çok İstenen Fiyat Alarmları (En çok alarm kurulan 5 ürün)
        $topAlertProducts = \App\Models\PriceAlert::select('product_id', DB::raw('count(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('product:id,title')
            ->get();
            
        $alertProductNames = $topAlertProducts->pluck('product.title')->map(function($title) {
            return \Illuminate\Support\Str::limit($title, 15);
        });
        $alertProductCounts = $topAlertProducts->pluck('total');

        // Stok Durum Özeti & Acil Stok Listesi (Stok <= 2 olan ilk 6 ürün, sadece sıfır ürünler)
        $outOfStock = \App\Models\Product::where('stock', 0)->where($nonSecondHandFilter)->count();
        $criticalStock = \App\Models\Product::whereBetween('stock', [1, 2])->where($nonSecondHandFilter)->count();
        $lowStock = \App\Models\Product::whereBetween('stock', [3, 5])->where($nonSecondHandFilter)->count();
        $sufficientStock = \App\Models\Product::where('stock', '>', 5)->where($nonSecondHandFilter)->count();
        $stockSummary = [$outOfStock, $criticalStock + $lowStock, $sufficientStock];

        $urgentStockProducts = \App\Models\Product::where('stock', '<=', 2)
            ->where($nonSecondHandFilter)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        // 3. Veri Tabloları
        $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();
        $activeAlerts = \App\Models\PriceAlert::with(['product', 'user'])->where('is_notified', false)->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingAlerts', 'lowStockProducts', 'dailyRevenue',
            'last7Days', 'salesTrend',
            'alertProductNames', 'alertProductCounts',
            'stockSummary', 'urgentStockProducts',
            'recentOrders', 'activeAlerts'
        ));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return redirect()->back();
        }

        $products = Product::where('title', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->get();
                    
        return view('admin.search_results', compact('products', 'query'));
    }
}
