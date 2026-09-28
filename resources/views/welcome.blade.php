@extends('layouts.app')

@section('title', 'Afi Bilişim | Geleceğin Teknolojisi, Güvenli Çözümler')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-4 relative z-10 py-16">
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-bold uppercase tracking-widest mb-6 shadow-sm">
        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span> Afi Bilişim Teknolojileri
    </div>

    <h1 class="text-4xl md:text-6xl font-heading font-black text-white tracking-tight max-w-3xl leading-tight mb-6">
        Geleceğin Teknolojisi <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-500 to-yellow-600">Afi Güvencesiyle</span>
    </h1>

    <p class="text-gray-300 text-base md:text-lg max-w-2xl font-medium mb-10 leading-relaxed">
        Test edilmiş ikinci el ve sıfır bilgisayar, oyuncu sistemleri, donanım bileşenleri ve teknik servis çözümleri.
    </p>

    <div class="flex flex-wrap items-center justify-center gap-4">
        <a href="{{ route('home') }}" class="bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-slate-950 font-black px-8 py-3.5 rounded-2xl shadow-lg shadow-yellow-500/25 transition-all transform hover:-translate-y-0.5 text-sm flex items-center gap-2">
            <i class="fa-solid fa-store"></i> Ana Sayfaya Git
        </a>
        <a href="{{ route('products.index') }}" class="bg-slate-900/80 hover:bg-slate-800 text-white font-bold px-8 py-3.5 rounded-2xl border border-gray-800 transition-all text-sm flex items-center gap-2">
            <i class="fa-solid fa-microchip text-yellow-500"></i> Ürün Kataloğu
        </a>
    </div>
</div>
@endsection
