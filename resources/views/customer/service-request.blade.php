@extends('layouts.app')

@section('title', 'Teknik Servis & Destek Talebi | Afi Bilişim')
@section('meta_description', 'Afi Bilişim teknik servis ve destek talebi oluşturun. İkinci el/sıfır bilgisayar, laptop veya güvenlik kamerası sorunlarınız için profesyonel destek.')

@section('content')
<div class="min-h-screen bg-afiDark text-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header Banner -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-yellow-500/10 border border-yellow-500/30 text-yellow-500 mb-4 shadow-lg shadow-yellow-500/5">
                <i class="fa-solid fa-wrench text-2xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-3">
                Teknik Servis & <span class="text-yellow-500">Destek Talebi</span>
            </h1>
            <p class="text-gray-400 text-base sm:text-lg max-w-xl mx-auto">
                Cihazınızla ilgili teknik sorunlar veya kamera sistemi kurulum talepleriniz için formu doldurun, uzman ekibimiz hızla dönüş yapsın.
            </p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-start gap-3 animate-fade-in">
                <i class="fa-solid fa-circle-check text-xl shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-[#161a23] border border-gray-800 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-yellow-500/5 rounded-full blur-3xl pointer-events-none"></div>

            <form action="{{ route('service-request.store') }}" method="POST" class="space-y-6 relative z-10">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Müşteri Adı -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">
                            Müşteri Adı Soyadı <span class="text-yellow-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}"
                                class="w-full pl-10 pr-4 py-3 bg-gray-900/80 border border-gray-700/80 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-colors text-sm"
                                placeholder="Örn: Ahmet Yılmaz">
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefon -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2">
                            Telefon Numarası <span class="text-yellow-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </span>
                            <input type="tel" name="phone" id="phone" required
                                value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}"
                                class="w-full pl-10 pr-4 py-3 bg-gray-900/80 border border-gray-700/80 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-colors text-sm"
                                placeholder="Örn: 0555 123 45 67">
                        </div>
                        @error('phone')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- E-posta -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">
                            E-posta Adresi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                                class="w-full pl-10 pr-4 py-3 bg-gray-900/80 border border-gray-700/80 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-colors text-sm"
                                placeholder="Örn: ahmet@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cihaz Modeli -->
                    <div>
                        <label for="device_model" class="block text-sm font-semibold text-gray-300 mb-2">
                            Cihaz Modeli / Türü <span class="text-yellow-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                                <i class="fa-solid fa-laptop text-sm"></i>
                            </span>
                            <input type="text" name="device_model" id="device_model" required
                                value="{{ old('device_model') }}"
                                class="w-full pl-10 pr-4 py-3 bg-gray-900/80 border border-gray-700/80 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-colors text-sm"
                                placeholder="Örn: Asus ROG Strix G15 / Hikvision DVR">
                        </div>
                        @error('device_model')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sorun Açıklaması -->
                <div>
                    <label for="issue_description" class="block text-sm font-semibold text-gray-300 mb-2">
                        Sorun ve Açıklama <span class="text-yellow-500">*</span>
                    </label>
                    <textarea name="issue_description" id="issue_description" rows="5" required
                        class="w-full p-4 bg-gray-900/80 border border-gray-700/80 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-colors text-sm resize-y"
                        placeholder="Lütfen yaşadığınız sorunu veya servis talebinizi detaylı olarak açıklayın (örn. Ekran açılmıyor, fan çok ses çıkarıyor, ek kamera montajı vb.)...">{{ old('issue_description') }}</textarea>
                    @error('issue_description')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 bg-yellow-500 hover:bg-yellow-400 text-afiDark font-bold rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-yellow-500/20 cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i>
                        Destek Talebini Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
