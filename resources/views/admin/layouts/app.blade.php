<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel | Afi Bilişim')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        adminBg: '#0f172a',
                        adminCard: '#1e293b',
                        adminBorder: '#334155',
                        adminText: '#cbd5e1',
                        adminYellow: '#eab308'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f172a; color: #cbd5e1; }
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }

        /* ─── ADMIN GLOBAL LOADER ─── */
        @keyframes afiSpin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes afiPulse {
            0%, 100% { opacity: 1;   transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.88); }
        }
        @keyframes afiLoaderIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        #afi-admin-loader {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 18px;
            animation: afiLoaderIn .2s ease;
        }
        #afi-admin-loader.is-active { display: flex !important; }
        .afi-admin-ring {
            position: relative; width: 64px; height: 64px;
        }
        .afi-admin-ring-track {
            position: absolute; inset: 0; border-radius: 50%;
            border: 3px solid rgba(234,179,8,0.15);
        }
        .afi-admin-ring-arc {
            position: absolute; inset: 0; border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #eab308;
            border-right-color: #ca8a04;
            animation: afiSpin .85s cubic-bezier(0.5,0,0.5,1) infinite;
        }
        .afi-admin-ring-core {
            position: absolute; inset: 18px; border-radius: 50%;
            background: rgba(234,179,8,0.10);
            display: flex; align-items: center; justify-content: center;
        }
        .afi-admin-ring-dot {
            width: 9px; height: 9px; border-radius: 50%;
            background: #eab308;
            box-shadow: 0 0 10px 3px rgba(234,179,8,0.5);
            animation: afiPulse 1.4s ease-in-out infinite;
        }
        .afi-admin-loader-label {
            color: #eab308;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            animation: afiPulse 1.6s ease-in-out infinite;
        }
        /* Buton loading state */
        .afi-btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.80;
        }
        .afi-btn-spinner {
            display: inline-block;
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: afiSpin .7s linear infinite;
            vertical-align: middle;
        }
        .afi-btn-spinner.yellow {
            border-color: rgba(234,179,8,0.3);
            border-top-color: #eab308;
        }
    </style>
</head>
<body class="font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-adminCard border-r border-adminBorder flex flex-col hidden md:flex shrink-0 transition-all duration-300 z-20 relative">
        <!-- Brand -->
        <div class="h-16 flex items-center justify-center border-b border-adminBorder shrink-0 px-4">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-black text-white flex items-center gap-2">
                <i class="fa-solid fa-microchip text-adminYellow"></i>
                <span>AFI<span class="text-adminYellow">BİLİŞİM</span></span>
            </a>
        </div>
        
        <!-- Nav Links -->
        <div class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-1">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4">Ana Menü</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
            </a>
            
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">E-Ticaret</p>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-box w-5 text-center"></i> Ürün Yönetimi
            </a>
            <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.banners.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-images w-5 text-center"></i> Slider / Bannerlar
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-tags w-5 text-center"></i> Kategoriler
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-cart-shopping w-5 text-center"></i> Siparişler
                </div>
                @php
                    $pendingCount = 0;
                    try {
                        if(\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                            $pendingCount = \App\Models\Order::where('status', 'Beklemede')->count();
                        }
                    } catch(\Exception $e) {}
                @endphp
                @if($pendingCount > 0)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Müşteri İlişkileri & Servis</p>
            <a href="{{ route('admin.service-requests.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.service-requests.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-headset w-5 text-center"></i> Destek ve Servis
                </div>
                @php
                    $newRequestsCount = 0;
                    try {
                        if(\Illuminate\Support\Facades\Schema::hasTable('service_requests')) {
                            $newRequestsCount = \App\Models\ServiceRequest::whereIn('status', ['Yeni', 'pending'])->count();
                        }
                    } catch(\Exception $e) {}
                @endphp
                @if($newRequestsCount > 0)
                    <span class="bg-amber-500 text-slate-950 text-xs font-black px-2 py-0.5 rounded-full">{{ $newRequestsCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reviews.pending') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reviews.*') || request()->routeIs('admin.contact-messages.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope w-5 text-center"></i> Gelen Mesajlar
                </div>
                @php
                    $unreadMsgCount = 0;
                    try {
                        if(\Illuminate\Support\Facades\Schema::hasTable('contact_messages')) {
                            $unreadMsgCount = \App\Models\ContactMessage::where('is_read', false)->count();
                        }
                    } catch(\Exception $e) {}
                @endphp
                @if($unreadMsgCount > 0)
                    <span class="bg-cyan-500 text-slate-950 text-xs font-black px-2 py-0.5 rounded-full">{{ $unreadMsgCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-users w-5 text-center"></i> Müşteriler
            </a>
            <a href="{{ route('admin.2fa.setup') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.2fa.*') ? 'bg-adminYellow text-slate-900 font-semibold' : 'text-adminText hover:bg-slate-800 hover:text-white' }} transition-colors">
                <i class="fa-solid fa-gear w-5 text-center"></i> Ayarlar
            </a>
        </div>
        
        <!-- User Profile -->
        <div class="p-4 border-t border-adminBorder shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold">
                    AD
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->check() ? auth()->user()->name : 'Admin Kullanıcı' }}</p>
                    <p class="text-xs text-slate-400 truncate">Yönetici</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors bg-transparent border-none p-0 cursor-pointer" title="Çıkış Yap">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Navbar -->
        <header class="h-16 bg-adminCard border-b border-adminBorder flex items-center justify-between px-4 lg:px-8 shrink-0 z-40 relative">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-adminText hover:text-white">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="hidden sm:flex items-center bg-slate-800 rounded-lg px-3 py-1.5 border border-adminBorder w-64">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 mr-2"></i>
                    <form action="{{ route('admin.products.index') }}" method="GET" class="w-full">
                        <input type="text" name="search" placeholder="Panelde ürün ara..." class="bg-transparent border-none outline-none text-sm text-white w-full placeholder-slate-500" value="{{ request('search') }}">
                    </form>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" target="_blank" class="text-sm font-medium text-slate-400 hover:text-white transition-colors hidden sm:flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Siteyi Görüntüle
                </a>
                
                <div class="w-px h-6 bg-adminBorder mx-1 hidden sm:block"></div>
                
                <!-- Dynamic Admin Notifications Bell & Dropdown -->
                @php
                    $navPendingOrders = [];
                    $navCriticalProducts = [];
                    $navNewServiceRequests = [];
                    $navContactMessages = [];
                    
                    try {
                        if(\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                            $navPendingOrders = \App\Models\Order::where('status', 'Beklemede')
                                ->orWhere(function($q) {
                                    $q->where('status', '!=', 'Beklemede')
                                      ->where('updated_at', '>=', now()->subHours(48));
                                })
                                ->latest()->take(3)->get();
                        }
                        if(\Illuminate\Support\Facades\Schema::hasTable('products')) {
                            $navCriticalProducts = \App\Models\Product::where('stock', '<=', 2)
                                ->where(function($query) {
                                    $query->whereNull('condition_type')
                                          ->orWhereNotIn('condition_type', ['second_hand', 'used', 'ikinci_el']);
                                })
                                ->take(3)->get();
                        }
                        if(\Illuminate\Support\Facades\Schema::hasTable('service_requests')) {
                            $navNewServiceRequests = \App\Models\ServiceRequest::whereIn('status', ['Yeni', 'pending'])
                                ->orWhere(function($q) {
                                    $q->whereNotIn('status', ['Yeni', 'pending'])
                                      ->where('updated_at', '>=', now()->subHours(48));
                                })
                                ->latest()->take(3)->get();
                        }
                        if(\Illuminate\Support\Facades\Schema::hasTable('contact_messages')) {
                            $navContactMessages = \App\Models\ContactMessage::where('is_read', false)
                                ->orWhere(function($q) {
                                    $q->where('is_read', true)
                                      ->where('updated_at', '>=', now()->subHours(48));
                                })
                                ->latest()->take(4)->get();
                        }
                    } catch(\Exception $e) {}

                    $unreadOrders = collect($navPendingOrders)->where('status', 'Beklemede')->count();
                    $unreadServices = collect($navNewServiceRequests)->filter(fn($s) => in_array($s->status, ['Yeni', 'pending']))->count();
                    $unreadContacts = collect($navContactMessages)->where('is_read', false)->count();
                    $unreadCriticalProducts = collect($navCriticalProducts)->where('is_read', false)->count();

                    $navTotalNotifs = $unreadOrders + $unreadServices + $unreadContacts + $unreadCriticalProducts;
                    $totalItemsInDropdown = count($navPendingOrders) + count($navCriticalProducts) + count($navNewServiceRequests) + count($navContactMessages);
                @endphp

                <div class="relative">
                    <button type="button" id="notifBellBtn" onclick="toggleAdminNotifications()" class="relative text-slate-400 hover:text-white transition-colors p-2 rounded-xl hover:bg-slate-800 focus:outline-none">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @if($navTotalNotifs > 0)
                            <span id="notifBadgeCount" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center animate-pulse">
                                {{ $navTotalNotifs > 9 ? '9+' : $navTotalNotifs }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown Menu -->
                    <div id="adminNotifDropdown" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-200" style="z-index: 99999 !important;">
                        <!-- Dropdown Header -->
                        <div class="bg-slate-800/80 backdrop-blur-md px-4 py-3 border-b border-slate-700/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bell text-adminYellow text-sm"></i>
                                <span class="font-bold text-white text-sm">Bildirim Merkezi</span>
                                <span id="notifBadgeHeaderCount" class="bg-adminYellow/20 text-adminYellow text-[10px] font-black px-2 py-0.5 rounded-full border border-adminYellow/30">{{ $navTotalNotifs }} Okunmamış</span>
                            </div>
                            @if($navTotalNotifs > 0)
                                <button type="button" onclick="markAllNotificationsAsRead(event)" class="text-xs text-adminYellow hover:underline font-semibold focus:outline-none">
                                    Tümünü Oku
                                </button>
                            @else
                                <span class="text-xs text-slate-400">Son 48 Saat</span>
                            @endif
                        </div>

                        <!-- Dropdown Content List -->
                        <div id="notifListContainer" class="max-h-80 overflow-y-auto divide-y divide-slate-800 sidebar-scroll">
                            @if($totalItemsInDropdown === 0)
                                <div class="p-6 text-center text-slate-500">
                                    <i class="fa-solid fa-circle-check text-3xl mb-2 text-emerald-500/50"></i>
                                    <p class="text-xs font-semibold">Tüm bildirimler okundu!</p>
                                    <p class="text-[11px] text-slate-600">İşlem bekleyen yeni bildirim bulunmuyor.</p>
                                </div>
                            @else
                                {{-- Yeni Siparişler --}}
                                @foreach($navPendingOrders as $order)
                                    @php $isUnread = ($order->status == 'Beklemede'); @endphp
                                    <div id="notif-item-order-{{ $order->id }}" class="flex items-center justify-between p-3.5 hover:bg-slate-800/60 transition-colors group {{ !$isUnread ? 'opacity-60 bg-slate-900/40' : '' }}">
                                        <a href="{{ route('admin.orders.index') }}" class="flex items-start gap-3 flex-1 overflow-hidden pr-2">
                                            <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30 flex items-center justify-center shrink-0 mt-0.5">
                                                <i class="fa-solid fa-cart-shopping text-xs"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-xs font-bold text-white group-hover:text-adminYellow transition-colors truncate">Sipariş #{{ $order->id }} ({{ $order->status }})</p>
                                                <p class="text-[11px] text-slate-400 font-mono">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</p>
                                                <span class="text-[10px] text-slate-500">{{ $order->created_at ? $order->created_at->diffForHumans() : 'Az önce' }}</span>
                                            </div>
                                        </a>
                                        @if($isUnread)
                                            <button type="button" onclick="markSingleNotifAsRead(event, 'order', {{ $order->id }}, this)" title="Okundu olarak işaretle" class="text-slate-500 hover:text-emerald-400 hover:bg-slate-800 p-1.5 rounded-lg transition-colors shrink-0 focus:outline-none">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @else
                                            <span title="Okundu (48 sa içinde otomatik kalkacak)" class="text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0">
                                                <i class="fa-solid fa-check-double text-[10px]"></i> Okundu
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                {{-- Kritik Stok Uyarıları --}}
                                @foreach($navCriticalProducts as $cp)
                                    <div id="notif-item-product-{{ $cp->id }}" class="flex items-center justify-between p-3.5 hover:bg-slate-800/60 transition-colors group {{ $cp->is_read ? 'opacity-60 bg-slate-900/40' : '' }}">
                                        <a href="{{ route('admin.products.edit', $cp->id) }}" class="flex items-start gap-3 flex-1 overflow-hidden pr-2">
                                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center shrink-0 mt-0.5">
                                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-xs font-bold text-white group-hover:text-amber-400 transition-colors truncate">{{ $cp->title }}</p>
                                                <p class="text-[11px] text-amber-400 font-bold">Kritik Stok: {{ $cp->stock }} Adet Kaldı!</p>
                                            </div>
                                        </a>
                                        @if(!$cp->is_read)
                                            <button type="button" onclick="markSingleNotifAsRead(event, 'product', {{ $cp->id }}, this)" title="Okundu olarak işaretle" class="text-slate-500 hover:text-emerald-400 hover:bg-slate-800 p-1.5 rounded-lg transition-colors shrink-0 focus:outline-none">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @else
                                            <span title="Okundu (48 sa içinde otomatik kalkacak)" class="text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0">
                                                <i class="fa-solid fa-check-double text-[10px]"></i> Okundu
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                {{-- Yeni Servis Talepleri --}}
                                @foreach($navNewServiceRequests as $sr)
                                    @php $isUnread = in_array($sr->status, ['Yeni', 'pending']); @endphp
                                    <div id="notif-item-service-{{ $sr->id }}" class="flex items-center justify-between p-3.5 hover:bg-slate-800/60 transition-colors group {{ !$isUnread ? 'opacity-60 bg-slate-900/40' : '' }}">
                                        <a href="{{ route('admin.service-requests.index') }}" class="flex items-start gap-3 flex-1 overflow-hidden pr-2">
                                            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center justify-center shrink-0 mt-0.5">
                                                <i class="fa-solid fa-wrench text-xs"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors truncate">Servis: {{ $sr->customer_name ?? 'Müşteri' }}</p>
                                                <p class="text-[11px] text-slate-400 truncate">{{ $sr->device_model ?? 'Cihaz' }} - {{ $sr->tracking_code }}</p>
                                            </div>
                                        </a>
                                        @if($isUnread)
                                            <button type="button" onclick="markSingleNotifAsRead(event, 'service', {{ $sr->id }}, this)" title="Okundu olarak işaretle" class="text-slate-500 hover:text-emerald-400 hover:bg-slate-800 p-1.5 rounded-lg transition-colors shrink-0 focus:outline-none">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @else
                                            <span title="Okundu (48 sa içinde otomatik kalkacak)" class="text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0">
                                                <i class="fa-solid fa-check-double text-[10px]"></i> Okundu
                                            </span>
                                        @endif
                                    </div>
                                @endforeach

                                {{-- İletişim Mesajları --}}
                                @foreach($navContactMessages as $msg)
                                    <div id="notif-item-contact-{{ $msg->id }}" class="flex items-center justify-between p-3.5 hover:bg-slate-800/60 transition-colors group {{ $msg->is_read ? 'opacity-60 bg-slate-900/40' : '' }}">
                                        <a href="{{ route('admin.contact-messages.index') }}" class="flex items-start gap-3 flex-1 overflow-hidden pr-2">
                                            <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center shrink-0 mt-0.5">
                                                <i class="fa-solid fa-envelope text-xs"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-xs font-bold text-white group-hover:text-cyan-300 transition-colors truncate">{{ $msg->name }}</p>
                                                <p class="text-[11px] text-slate-400 truncate">{{ $msg->subject ?? 'İletişim Mesajı' }}</p>
                                            </div>
                                        </a>
                                        @if(!$msg->is_read)
                                            <button type="button" onclick="markSingleNotifAsRead(event, 'contact', {{ $msg->id }}, this)" title="Okundu olarak işaretle" class="text-slate-500 hover:text-emerald-400 hover:bg-slate-800 p-1.5 rounded-lg transition-colors shrink-0 focus:outline-none">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @else
                                            <span title="Okundu (48 sa içinde otomatik kalkacak)" class="text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0">
                                                <i class="fa-solid fa-check-double text-[10px]"></i> Okundu
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Dropdown Footer -->
                        <div class="bg-slate-800/80 p-2.5 text-center border-t border-slate-700/80">
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors block py-1">
                                Panel Özetine Git <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </header>


        <!-- Main Area -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-8">
            @yield('content')
        </main>
        
    </div>

    {{-- ═══ ADMIN GLOBAL OVERLAY LOADER ═══ --}}
    <div id="afi-admin-loader" role="status" aria-label="Yükleniyor" aria-live="polite">
        <div class="afi-admin-ring">
            <div class="afi-admin-ring-track"></div>
            <div class="afi-admin-ring-arc"></div>
            <div class="afi-admin-ring-core">
                <div class="afi-admin-ring-dot"></div>
            </div>
        </div>
        <p class="afi-admin-loader-label" id="afi-admin-loader-label">Yükleniyor...</p>
    </div>

    @stack('scripts')

    <script>
    /* Admin Notifications Dropdown Toggle */
    function toggleAdminNotifications() {
        const dropdown = document.getElementById('adminNotifDropdown');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    document.addEventListener('click', function(e) {
        const btn = document.getElementById('notifBellBtn');
        const dropdown = document.getElementById('adminNotifDropdown');
        if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    let unreadNotifCount = {{ $navTotalNotifs }};

    function updateNotifBadges(newCount) {
        unreadNotifCount = Math.max(0, newCount);
        const notifBadge = document.getElementById('notifBadgeCount');
        const headerCount = document.getElementById('notifBadgeHeaderCount');

        if (unreadNotifCount > 0) {
            if (notifBadge) {
                notifBadge.style.display = 'flex';
                notifBadge.innerText = unreadNotifCount > 9 ? '9+' : unreadNotifCount;
            }
            if (headerCount) {
                headerCount.innerText = unreadNotifCount + ' Okunmamış';
                headerCount.className = 'bg-adminYellow/20 text-adminYellow text-[10px] font-black px-2 py-0.5 rounded-full border border-adminYellow/30';
            }
        } else {
            if (notifBadge) notifBadge.style.display = 'none';
            if (headerCount) {
                headerCount.innerText = '0 Okunmamış';
                headerCount.className = 'bg-slate-700/50 text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-full';
            }
        }
    }

    async function markSingleNotifAsRead(e, type, id, btnElement) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        try {
            const response = await fetch("{{ route('admin.notifications.mark-single-read') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ type: type, id: id })
            });
            const data = await response.json();
            if (data.success) {
                updateNotifBadges(unreadNotifCount - 1);

                const itemRow = document.getElementById(`notif-item-${type}-${id}`);
                if (itemRow) {
                    itemRow.classList.add('opacity-60', 'bg-slate-900/40');
                }
                if (btnElement) {
                    const badgeSpan = document.createElement('span');
                    badgeSpan.title = 'Okundu (48 sa içinde otomatik kalkacak)';
                    badgeSpan.className = 'text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0';
                    badgeSpan.innerHTML = '<i class="fa-solid fa-check-double text-[10px]"></i> Okundu';
                    btnElement.parentNode.replaceChild(badgeSpan, btnElement);
                }
            }
        } catch (err) {
            console.error("Tekil bildirim güncellenemedi:", err);
        }
    }

    async function markAllNotificationsAsRead(e) {
        if (e) e.stopPropagation();
        try {
            const response = await fetch("{{ route('admin.notifications.mark-all-read') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            });
            const data = await response.json();
            if (data.success) {
                updateNotifBadges(0);
                
                // Mark all visible rows as read visually
                document.querySelectorAll('#notifListContainer button[onclick*="markSingleNotifAsRead"]').forEach(btn => {
                    const badgeSpan = document.createElement('span');
                    badgeSpan.title = 'Okundu (48 sa içinde otomatik kalkacak)';
                    badgeSpan.className = 'text-emerald-400 text-[10px] font-semibold px-2 py-0.5 rounded flex items-center gap-1 opacity-75 shrink-0';
                    badgeSpan.innerHTML = '<i class="fa-solid fa-check-double text-[10px]"></i> Okundu';
                    if (btn.parentNode) {
                        btn.parentNode.replaceChild(badgeSpan, btn);
                    }
                });

                document.querySelectorAll('#notifListContainer > div').forEach(row => {
                    row.classList.add('opacity-60', 'bg-slate-900/40');
                });
            }
        } catch (err) {
            console.error("Bildirimler güncellenemedi:", err);
        }
    }

    /* Admin AfiLoader API */

    (function () {
        const overlay = document.getElementById('afi-admin-loader');
        const label   = document.getElementById('afi-admin-loader-label');

        window.AfiLoader = {
            show(msg) {
                if (!overlay) return;
                if (msg && label) label.textContent = msg;
                overlay.classList.add('is-active');
                document.body.style.overflow = 'hidden';
            },
            hide() {
                if (!overlay) return;
                overlay.classList.remove('is-active');
                document.body.style.overflow = '';
            },
            btn(el, msg) {
                if (!el) return;
                el.dataset.afiOriginal = el.innerHTML;
                el.classList.add('afi-btn-loading');
                el.disabled = true;
                const isYellow = el.classList.contains('bg-adminYellow') || el.classList.contains('bg-yellow-400');
                const spinnerClass = isYellow ? 'yellow' : '';
                el.innerHTML = `<span class="afi-btn-spinner ${spinnerClass}"></span>${msg ? ' ' + msg : ''}`;
            },
            btnReset(el) {
                if (!el || !el.dataset.afiOriginal) return;
                el.innerHTML   = el.dataset.afiOriginal;
                el.disabled    = false;
                el.classList.remove('afi-btn-loading');
                delete el.dataset.afiOriginal;
            }
        };

        /* Sayfa geçişlerinde loader (admin sidebar linkleri) */
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href]');
            if (!anchor) return;
            const href = anchor.getAttribute('href');
            if (!href
                || href.startsWith('#')
                || href.startsWith('javascript')
                || anchor.target === '_blank'
                || e.ctrlKey || e.metaKey || e.shiftKey
                || anchor.dataset.noLoader === 'true'
            ) return;
            try {
                const url = new URL(href, window.location.origin);
                if (url.pathname === window.location.pathname && url.search === window.location.search) return;
            } catch (_) {}
            AfiLoader.show('Sayfa yükleniyor...');
        });

        window.addEventListener('pageshow', function (e) {
            if (e.persisted) AfiLoader.hide();
        });
    })();
    </script>
</body>
</html>
