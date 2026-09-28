@extends('layouts.app')

@section('title', 'Telefon Doğrulama | Afi Bilişim')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-afi-dark-gradient relative overflow-hidden">
    <!-- Dekoratif Arka Plan -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-yellow-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-pulse"></div>

    <div class="max-w-md w-full space-y-8 bg-[#1e293b] p-10 rounded-3xl border border-[#334155] shadow-2xl relative z-10">
        <div>
            <div class="w-16 h-16 bg-[#eab308]/20 text-[#eab308] rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
                <i class="fa-solid fa-mobile-screen"></i>
            </div>
            <h2 class="text-center text-3xl font-black text-white tracking-tight">Telefon Doğrulama</h2>
            <p class="mt-2 text-center text-sm text-slate-400">Telefonunuza gönderilen 6 haneli doğrulama kodunu girin.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm font-semibold text-center">
                <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm font-semibold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 space-y-6">
            <form action="{{ route('verification.phone.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="code" class="block text-sm font-semibold text-slate-300 mb-1.5 text-center">Doğrulama Kodu</label>
                    <input id="code" name="code" type="text" maxlength="6" required class="w-full bg-slate-800 border border-[#334155] text-white text-center text-2xl tracking-widest rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block p-4 transition-colors placeholder-slate-600 font-bold" placeholder="------">
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#eab308] hover:bg-yellow-400 text-slate-900 font-bold text-base py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20 mt-4">
                    <i class="fa-solid fa-check"></i> Doğrula
                </button>
            </form>
        </div>

        <div class="mt-6 border-t border-[#334155] pt-6">
            <form action="{{ route('verification.phone.resend') }}" method="POST" class="text-center">
                @csrf
                <p class="text-sm text-slate-400 mb-2">Kodu almadınız mı?</p>
                <button type="submit" class="font-bold text-[#eab308] hover:underline bg-transparent border-none cursor-pointer">
                    Tekrar Gönder
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
