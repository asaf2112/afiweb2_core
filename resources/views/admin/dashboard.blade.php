@extends('admin.layouts.app')

@section('title', 'Yönetici Analitik Paneli | Afi Bilişim')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-adminYellow"></i> Yönetici Analitik Paneli
            </h1>
            <p class="text-slate-400 text-sm">Afi Bilişim e-ticaret performansınızın detaylı özet görünümü.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index', ['stock_status' => 'critical']) }}" 
               class="bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 border border-amber-500/40 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Kritik Stoklar ({{ $lowStockProducts }})
            </a>
        </div>
    </div>

    <!-- 🚨 ACİL STOK TEDARİK BİLDİRİMİ / WIDGET -->
    @if(isset($urgentStockProducts) && count($urgentStockProducts) > 0)
        <div class="mb-8 bg-gradient-to-r from-rose-950/80 via-amber-950/60 to-slate-900 border border-rose-500/40 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 opacity-10 text-rose-500 text-9xl pointer-events-none">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <div class="relative z-10 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-rose-500/20 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/40 flex items-center justify-center shrink-0 animate-pulse">
                            <i class="fa-solid fa-bell text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white flex items-center gap-2">
                                ACİL TEDARİK & STOK UYARISI
                                <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
                                    {{ count($urgentStockProducts) }} Ürün Kritik
                                </span>
                            </h3>
                            <p class="text-slate-300 text-xs mt-0.5">Aşağıdaki ürünlerin stokları tükenmiş veya kritik seviyeye (≤ 2) düşmüştür.</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.products.index', ['stock_status' => 'critical']) }}" 
                       class="bg-amber-400 hover:bg-yellow-300 text-slate-950 font-black px-5 py-2.5 rounded-xl transition shadow-lg text-xs flex items-center gap-2 whitespace-nowrap self-start">
                        Tüm Kritik Stokları Listele <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Urgent Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 pt-1">
                    @foreach($urgentStockProducts as $urgProd)
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-12 h-12 bg-slate-800 rounded-xl border border-slate-700 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($urgProd->main_image)
                                        @php
                                            $imgUrl = \Illuminate\Support\Str::startsWith($urgProd->main_image, ['http']) 
                                                ? $urgProd->main_image 
                                                : asset($urgProd->main_image);
                                        @endphp
                                        <img src="{{ $imgUrl }}" class="w-full h-full object-contain" alt="{{ $urgProd->title }}">
                                    @else
                                        <i class="fa-solid fa-image text-slate-600"></i>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-xs font-bold text-white truncate leading-tight" title="{{ $urgProd->title }}">{{ $urgProd->title }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $urgProd->category ? $urgProd->category->name : 'Kategorisiz' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end shrink-0 gap-1">
                                @if($urgProd->stock == 0)
                                    <span class="bg-rose-500/20 text-rose-400 border border-rose-500/40 text-[10px] font-black px-2 py-0.5 rounded-md animate-pulse">
                                        Stok Yok (0)
                                    </span>
                                @else
                                    <span class="bg-amber-500/20 text-amber-400 border border-amber-500/40 text-[10px] font-black px-2 py-0.5 rounded-md">
                                        Stok: {{ $urgProd->stock }}
                                    </span>
                                @endif

                                <a href="{{ route('admin.products.edit', $urgProd->id) }}" class="text-[10px] text-amber-400 hover:underline font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-pen"></i> Stok Ekle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Hızlı Aksiyon Kartları -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Toplam Sipariş -->
        <a href="{{ route('admin.orders.index') }}" class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg hover:border-[#eab308] transition-colors group relative overflow-hidden block">
            <div class="absolute -right-4 -top-4 text-slate-800 opacity-50 group-hover:text-[#eab308] group-hover:opacity-10 transition-all duration-500 transform group-hover:scale-150">
                <i class="fa-solid fa-cart-shopping text-7xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Toplam Sipariş</p>
                        <h3 class="text-3xl font-black text-white group-hover:text-[#eab308] transition-colors">{{ number_format($totalOrders) }}</h3>
                    </div>
                </div>
            </div>
        </a>

        <!-- Card 2: Bekleyen Fiyat Alarmları -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg hover:border-emerald-500 transition-colors group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-slate-800 opacity-50 group-hover:text-emerald-500 group-hover:opacity-10 transition-all duration-500 transform group-hover:scale-150">
                <i class="fa-solid fa-bell text-7xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Bekleyen Fiyat Alarmları</p>
                        <h3 class="text-3xl font-black text-white group-hover:text-emerald-500 transition-colors">{{ number_format($pendingAlerts) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Düşük Stoklu Ürünler -->
        <a href="{{ route('admin.products.index', ['stock_status' => 'critical']) }}" class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg hover:border-red-500 transition-colors group relative overflow-hidden block">
            <div class="absolute -right-4 -top-4 text-slate-800 opacity-50 group-hover:text-red-500 group-hover:opacity-10 transition-all duration-500 transform group-hover:scale-150">
                <i class="fa-solid fa-boxes-stacked text-7xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Düşük Stoklu Ürünler</p>
                        <h3 class="text-3xl font-black text-white group-hover:text-red-500 transition-colors">{{ number_format($lowStockProducts) }}</h3>
                    </div>
                </div>
            </div>
        </a>

        <!-- Card 4: Günlük Ciro -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg hover:border-blue-500 transition-colors group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-slate-800 opacity-50 group-hover:text-blue-500 group-hover:opacity-10 transition-all duration-500 transform group-hover:scale-150">
                <i class="fa-solid fa-wallet text-7xl"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Günlük Ciro</p>
                        <h3 class="text-3xl font-black text-white group-hover:text-blue-500 transition-colors">{{ number_format($dailyRevenue, 2) }} ₺</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Line Chart: Günlük Satış Trendi -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg">
            <h2 class="text-lg font-bold text-white mb-4">Günlük Satış Trendi (Son 7 Gün)</h2>
            <div id="salesChart" class="w-full h-80"></div>
        </div>

        <!-- Bar Chart: En Çok İstenen Fiyat Alarmları -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg">
            <h2 class="text-lg font-bold text-white mb-4">En Çok Alarm Kurulan Ürünler</h2>
            <div id="alertsChart" class="w-full h-80"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Pie Chart: Stok Durum Özeti -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg flex flex-col items-center justify-center">
            <h2 class="text-lg font-bold text-white mb-4 w-full text-left">Stok Durum Özeti</h2>
            <div id="stockChart" class="w-full flex justify-center"></div>
        </div>

        <!-- Son Siparişler Tablosu -->
        <div class="lg:col-span-2 bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-white">Son Siparişler</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[#eab308] text-sm hover:underline">Tümünü Gör</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-800 border-b border-[#334155]">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Sipariş No</th>
                            <th class="px-4 py-3">Müşteri</th>
                            <th class="px-4 py-3">Tutar</th>
                            <th class="px-4 py-3">Durum</th>
                            <th class="px-4 py-3 rounded-tr-lg">Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr class="border-b border-[#334155] hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-white">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-[#eab308]">{{ $order->reference_code ?? '#' . $order->id }}</a>
                            </td>
                            <td class="px-4 py-3">{{ $order->user->name ?? 'Ziyaretçi' }}</td>
                            <td class="px-4 py-3 font-bold text-white">{{ number_format($order->total_amount, 2) }} ₺</td>
                            <td class="px-4 py-3">
                                @php
                                    $color = 'slate';
                                    if(in_array($order->status, ['Bekliyor', 'Beklemede'])) $color = 'yellow';
                                    if($order->status == 'Onaylandı') $color = 'blue';
                                    if(in_array($order->status, ['Kargoya Verildi', 'Kargolandı'])) $color = 'purple';
                                    if($order->status == 'Tamamlandı') $color = 'green';
                                    if($order->status == 'İptal Edildi') $color = 'red';
                                @endphp
                                <span class="bg-{{ $color }}-500/20 text-{{ $color }}-400 px-2 py-1 rounded text-xs font-bold">{{ $order->status }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Henüz sipariş bulunmuyor.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aktif Fiyat Alarmları Tablosu -->
    <div class="bg-[#1e293b] rounded-2xl p-6 border border-[#334155] shadow-lg mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-white">Aktif Fiyat Alarmları</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="text-xs text-slate-500 uppercase bg-slate-800 border-b border-[#334155]">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Ürün</th>
                        <th class="px-4 py-3">Müşteri/İletişim</th>
                        <th class="px-4 py-3">Mevcut Fiyat</th>
                        <th class="px-4 py-3">Hedef Fiyat</th>
                        <th class="px-4 py-3 rounded-tr-lg">Kayıt Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeAlerts as $alert)
                    <tr class="border-b border-[#334155] hover:bg-slate-800/50 transition-colors">
                        <td class="px-4 py-3 font-medium text-white flex items-center gap-3">
                            @if($alert->product && $alert->product->main_image)
                                <img src="{{ asset($alert->product->main_image) }}" class="w-8 h-8 rounded object-cover">
                            @else
                                <div class="w-8 h-8 rounded bg-slate-700 flex items-center justify-center text-xs"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <a href="{{ route('admin.products.edit', $alert->product_id) }}" class="hover:text-[#eab308]">{{ \Illuminate\Support\Str::limit($alert->product->title ?? 'Bilinmeyen Ürün', 40) }}</a>
                        </td>
                        <td class="px-4 py-3">
                            @if($alert->user_id)
                                {{ $alert->user->name }} <br><span class="text-xs text-slate-500">{{ $alert->user->email ?? $alert->user->phone }}</span>
                            @else
                                <span class="text-slate-300">{{ $alert->contact_info }}</span> <span class="text-xs bg-slate-700 px-1 rounded ml-1">Misafir</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-300">{{ number_format($alert->product->price ?? 0, 2) }} ₺</td>
                        <td class="px-4 py-3 font-bold text-emerald-400">{{ number_format($alert->target_price, 2) }} ₺</td>
                        <td class="px-4 py-3">{{ $alert->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Bekleyen aktif fiyat alarmı bulunmuyor.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ApexCharts Configurations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Günlük Satış Trendi (Line Chart)
            var salesOptions = {
                series: [{
                    name: 'Ciro (₺)',
                    data: @js($salesTrend)
                }],
                chart: {
                    height: 320,
                    type: 'area',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    background: 'transparent'
                },
                colors: ['#eab308'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: @js($last7Days),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8' } }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8' }, formatter: (val) => { return val.toLocaleString('tr-TR') + ' ₺' } }
                },
                grid: {
                    borderColor: '#334155',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                theme: { mode: 'dark' }
            };
            var salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
            salesChart.render();

            // 2. En Çok İstenen Fiyat Alarmları (Bar Chart)
            var alertsOptions = {
                series: [{
                    name: 'Alarm Sayısı',
                    data: @js($alertProductCounts)
                }],
                chart: {
                    height: 320,
                    type: 'bar',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    background: 'transparent'
                },
                colors: ['#3b82f6'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '40%',
                        distributed: true,
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: @js($alertProductNames),
                    labels: { style: { colors: '#94a3b8' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8' } }
                },
                grid: {
                    borderColor: '#334155',
                    strokeDashArray: 4
                },
                theme: { mode: 'dark' },
                legend: { show: false }
            };
            var alertsChart = new ApexCharts(document.querySelector("#alertsChart"), alertsOptions);
            alertsChart.render();

            // 3. Stok Durum Özeti (Pie/Donut Chart)
            var stockOptions = {
                series: @js($stockSummary),
                labels: ['Tükendi (0)', 'Kritik / Azalan (1-5)', 'Yeterli Stok (>5)'],
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },
                colors: ['#ef4444', '#f59e0b', '#10b981'],
                stroke: { show: true, colors: '#1e293b', width: 2 },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { color: '#94a3b8' },
                                value: { color: '#fff', fontSize: '24px', fontWeight: 'bold' },
                                total: {
                                    show: true,
                                    label: 'Toplam Ürün',
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: { colors: '#94a3b8' }
                },
                theme: { mode: 'dark' }
            };
            var stockChart = new ApexCharts(document.querySelector("#stockChart"), stockOptions);
            stockChart.render();
        });
    </script>
@endsection
