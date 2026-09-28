@extends('admin.layouts.app')

@section('title', 'İki Aşamalı Doğrulama (2FA) Kurulumu | Afi Bilişim')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white mb-2">Güvenlik: 2FA Kurulumu</h1>
    <p class="text-slate-400 text-sm">Yönetim panelini daha güvenli hale getirmek için İki Aşamalı Doğrulamayı (2FA) aktifleştirin.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- QR Kodu Bölümü -->
    <div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg p-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-slate-800 text-adminYellow flex items-center justify-center text-2xl border border-adminBorder shadow-inner">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">1. Uygulamayı İndirin ve Taratın</h2>
                <p class="text-sm text-slate-400">Telefonunuza Google Authenticator uygulamasını indirin ve aşağıdaki QR kodu okutun.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl w-64 h-64 mx-auto mb-6 flex items-center justify-center border-4 border-slate-700 shadow-xl overflow-hidden">
            <img src="data:image/svg+xml;base64,{{ base64_encode($qrCodeUrl) }}" alt="QR Code" class="w-full h-full object-contain">
        </div>

        <div class="text-center">
            <p class="text-xs text-slate-400 mb-1">QR kod okunamıyorsa aşağıdaki gizli anahtarı manuel girebilirsiniz:</p>
            <code class="bg-slate-800 text-adminYellow px-4 py-2 rounded-lg border border-adminBorder font-mono font-bold tracking-widest inline-block select-all">
                {{ $secret }}
            </code>
        </div>
    </div>

    <!-- Doğrulama Bölümü -->
    <div class="bg-adminCard rounded-2xl border border-adminBorder shadow-lg p-8 flex flex-col justify-center">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-slate-800 text-adminYellow flex items-center justify-center text-2xl border border-adminBorder shadow-inner">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">2. Kodu Doğrulayın</h2>
                <p class="text-sm text-slate-400">Uygulamada gözüken 6 haneli doğrulama kodunu girerek işlemi tamamlayın.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6 text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-semibold flex flex-col gap-1">
                @foreach($errors->all() as $error)
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.2fa.setup.post') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-6">
                <label for="totp_code" class="block text-sm font-semibold text-slate-300 mb-2">6 Haneli Doğrulama Kodu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <input type="text" id="totp_code" name="totp_code" class="w-full bg-slate-800 border border-adminBorder text-white text-lg font-bold rounded-xl focus:ring-2 focus:ring-adminYellow focus:border-transparent block pl-12 p-3.5 tracking-[0.2em] placeholder-slate-600 transition-all" placeholder="123456" maxlength="6" required autocomplete="off">
                </div>
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-3 bg-adminYellow hover:bg-yellow-400 text-slate-900 font-black text-lg py-4 px-4 rounded-xl transition-all shadow-lg shadow-yellow-500/20">
                <i class="fa-solid fa-lock text-xl"></i> Kurulumu Tamamla ve Kilitle
            </button>
        </form>
    </div>
</div>
@endsection
