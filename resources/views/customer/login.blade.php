@extends('layouts.app')

@section('title', 'Müşteri Girişi | Afi Bilişim')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-afi-dark-gradient relative overflow-hidden">
    <!-- Dekoratif Arka Plan -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-yellow-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-pulse"></div>

    <div class="max-w-md w-full space-y-8 bg-[#1e293b] p-10 rounded-3xl border border-[#334155] shadow-2xl relative z-10">
        <div>
            <h2 class="text-center text-3xl font-black text-white tracking-tight">Müşteri Girişi</h2>
            <p class="mt-2 text-center text-sm text-slate-400">Hesabınıza güvenli bir şekilde giriş yapın</p>
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
            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-gray-100 text-gray-900 font-bold py-3 px-4 rounded-xl transition-all shadow-md">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google Logo">
                Google ile Giriş Yap
            </a>

            <!-- Divider -->
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-[#334155]"></div>
                <span class="flex-shrink-0 mx-4 text-slate-500 text-sm font-medium">veya e-posta ile</span>
                <div class="flex-grow border-t border-[#334155]"></div>
            </div>

            <!-- Classic Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-1.5">E-posta Adresi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="ornek@mail.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-300 mb-1.5">Şifre</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-[#eab308] focus:ring-[#eab308] mr-2 border-[#334155] bg-slate-800"> Beni Hatırla
                    </label>
                    <a href="{{ route('password.request') }}" class="font-bold text-[#eab308] hover:text-yellow-400 transition">Şifremi Unuttum</a>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#eab308] hover:bg-yellow-400 text-slate-900 font-bold text-base py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20 mt-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Giriş Yap
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-400 mt-6">
            Hesabın yok mu? <a href="{{ route('register') }}" class="font-bold text-[#eab308] hover:underline">Hemen Kayıt Ol</a>
        </p>
    </div>
</div>
@endsection
