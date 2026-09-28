@extends('layouts.app')

@section('title', 'Kayıt Ol | Afi Bilişim')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-afi-dark-gradient relative overflow-hidden">
    <!-- Dekoratif Arka Plan -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-yellow-500 rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-pulse"></div>

    <div class="max-w-md w-full space-y-8 bg-[#1e293b] p-10 rounded-3xl border border-[#334155] shadow-2xl relative z-10">
        <div>
            <h2 class="text-center text-3xl font-black text-white tracking-tight">Aramıza Katıl</h2>
            <p class="mt-2 text-center text-sm text-slate-400">Geleceğin teknolojisine hemen bağlan</p>
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
                Google ile Kayıt Ol
            </a>

            <!-- Divider -->
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-[#334155]"></div>
                <span class="flex-shrink-0 mx-4 text-slate-500 text-sm font-medium">veya e-posta ile</span>
                <div class="flex-grow border-t border-[#334155]"></div>
            </div>

            <!-- Classic Registration Form -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-300 mb-1.5">Ad Soyad</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="John Doe">
                    </div>
                </div>

                <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 px-4 py-3 rounded-xl text-xs font-semibold mb-4">
                    <i class="fa-solid fa-circle-info mr-1"></i> E-posta veya Telefon numarasından sadece birini girmeniz yeterlidir.
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-300 mb-1.5">E-posta Adresi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="ornek@mail.com">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-300 mb-1.5">Telefon Numarası</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="05XX XXX XX XX">
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-300 mb-1.5">Şifre (Tekrar)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full bg-slate-800 border border-[#334155] text-white text-sm rounded-xl focus:ring-2 focus:ring-[#eab308] focus:border-transparent block pl-10 p-3 transition-colors placeholder-slate-600" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" id="register-submit-btn" class="w-full flex items-center justify-center gap-2 bg-[#eab308] hover:bg-yellow-400 text-slate-900 font-bold text-base py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20 mt-2">
                    <i class="fa-solid fa-user-plus"></i> Hesabı Oluştur
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-400 mt-6">
            Zaten hesabın var mı? <a href="{{ route('login') }}" class="font-bold text-[#eab308] hover:underline">Giriş Yap</a>
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        
        phoneInput.addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,3})(\d{0,2})(\d{0,2})/);
            if (!x[1]) {
                e.target.value = '';
                return;
            }
            if (x[1] && !x[1].startsWith('05')) {
                x[1] = '05';
            }
            
            e.target.value = !x[2] ? x[1] : x[1] + ' ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
        });

        /* ── Kayıt formu submit loader ── */
        const registerForm = document.querySelector('form[action*="register"]');
        const registerBtn  = document.getElementById('register-submit-btn');
        if (registerForm && registerBtn) {
            registerForm.addEventListener('submit', function () {
                if (window.AfiLoader) {
                    AfiLoader.show('Hesap oluşturuluyor...');
                    AfiLoader.btn(registerBtn, 'Kaydediliyor...');
                }
            });
        }
    });
</script>
@endsection
