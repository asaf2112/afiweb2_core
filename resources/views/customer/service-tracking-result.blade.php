@extends('layouts.app')

@section('title', 'Servis Takip Sonucu | Afi Bilişim')

@section('content')
<div class="bg-afiGray py-16 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 md:px-12">
        
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-sm font-bold text-gray-500 hover:text-afiDark flex items-center gap-2 transition">
                <i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön
            </a>
        </div>

        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-3xl border border-yellow-200 shrink-0">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <div>
                        <span class="bg-yellow-500 text-afiDark text-xs font-black px-3 py-1 rounded-lg uppercase tracking-wider">
                            {{ $responseData['tracking_code'] }}
                        </span>
                        <h1 class="text-2xl md:text-3xl font-black text-afiDark mt-2">{{ $responseData['device_model'] }}</h1>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $responseData['service_type'] }}</p>
                    </div>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-xs text-gray-400 block">Kayıt Tarihi</span>
                    <span class="text-sm font-bold text-afiDark">{{ $responseData['created_at_formatted'] }}</span>
                    <span class="text-xs text-gray-400 block mt-1">Müşteri: <strong class="text-afiDark">{{ $responseData['customer_name'] }}</strong></span>
                </div>
            </div>

            <!-- Stepper Progress Bar -->
            <div class="mb-10 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6 text-center">Cihaz Onarım Aşamaları</h3>
                <div class="grid grid-cols-3 gap-2 text-center">
                    
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-full {{ $responseData['stage'] >= 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-lg font-bold shadow-md">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <span class="text-xs font-bold {{ $responseData['stage'] >= 1 ? 'text-green-600' : 'text-gray-400' }}">1. Kayıt Alındı</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-full {{ $responseData['stage'] == 2 ? 'bg-yellow-500 text-afiDark animate-bounce' : ($responseData['stage'] > 2 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400') }} flex items-center justify-center text-lg font-bold shadow-md">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                        <span class="text-xs font-bold {{ $responseData['stage'] >= 2 ? 'text-yellow-600' : 'text-gray-400' }}">2. İnceleme & İşlemde</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-full {{ $responseData['stage'] >= 3 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-lg font-bold shadow-md">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="text-xs font-bold {{ $responseData['stage'] >= 3 ? 'text-green-600' : 'text-gray-400' }}">3. Tamamlandı / Hazır</span>
                    </div>

                </div>
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm">
                    <span class="text-gray-400 font-bold uppercase text-xs">Bildirilen Arıza / Sorun:</span>
                    <p class="text-afiDark font-semibold mt-1">{{ $responseData['issue_description'] }}</p>
                </div>

                @if(!empty($responseData['admin_note']))
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl text-sm">
                    <span class="text-yellow-800 font-bold uppercase text-xs flex items-center gap-1">
                        <i class="fa-solid fa-user-gear"></i> Servis Tekniker Notu:
                    </span>
                    <p class="text-yellow-900 mt-1 font-medium">{{ $responseData['admin_note'] }}</p>
                </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <button type="button" onclick="openServiceTrackingModal()" class="px-6 py-3 bg-afiDark text-white font-bold rounded-xl hover:bg-yellow-500 hover:text-afiDark transition">
                    Başka Bir Cihaz Sorgula
                </button>
            </div>

        </div>

    </div>
</div>
@endsection
