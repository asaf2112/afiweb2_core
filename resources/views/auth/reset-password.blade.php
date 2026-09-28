@extends('layouts.app')

@section('title', 'Yeni Şifre Belirle | Afi Bilişim')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-afi-dark-gradient relative overflow-hidden">
    <!-- Dekoratif Arka Plan -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-yellow-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-pulse"></div>

    <div class="max-w-md w-full space-y-8 bg-[#1e293b] p-10 rounded-3xl border border-[#334155] shadow-2xl relative z-10">
        <div>
            <h2 class="text-center text-3xl font-black text-white tracking-tight">Yeni Şifre Belirle</h2>
            <p class="mt-2 text-center text-sm text-slate-400">Lütfen hesabınız için yeni ve güvenli bir şifre girin.</p>
        </div>

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
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Token Bilgisi Gizli Olarak Gönderilir -->
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-1.5">E-posta Adresi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ request()->email ?? old('email') }}" required readonly class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl block pl-10 p-3 opacity-70 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-300 mb-1.5">Yeni Şifre</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-300 mb-1.5">Yeni Şifre (Tekrar)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#eab308] hover:bg-yellow-400 text-slate-900 font-bold text-base py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20 mt-4">
                    <i class="fa-solid fa-key"></i> Şifreyi Güncelle
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
