@extends('layouts.app')

@section('title', 'İletişim | Afi Bilişim')

@section('content')
<div class="bg-afiGray py-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="bg-yellow-100 text-yellow-800 text-xs font-black uppercase tracking-wider px-4 py-1.5 rounded-full inline-block mb-3">
                <i class="fa-solid fa-headset mr-1"></i> Müşteri Destek & İletişim
            </span>
            <h1 class="text-5xl md:text-6xl font-black text-afiDark mb-4">Bizimle İletişime Geçin</h1>
            <p class="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Sorularınız, teknik destek talepleriniz veya önerileriniz için WhatsApp hattımızdan yazabilir veya doğrudan iletişim formumuzu doldurabilirsiniz.
            </p>
        </div>

        <!-- Balanced 2-Column Layout: WhatsApp Card (Left) & E-Posta Form Card (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto mb-16 items-stretch">
            
            <!-- Left Column: WhatsApp & Quick Info Card (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
                
                <!-- Main WhatsApp Card -->
                <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-gray-100 flex-1 flex flex-col justify-between relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-green-500/10 rounded-full blur-3xl group-hover:bg-green-500/20 transition-all duration-500"></div>
                    
                    <div>
                        <div class="w-20 h-20 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-4xl mb-6 shadow-sm border border-green-100 group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-3">
                            <i class="fa-solid fa-bolt mr-1"></i> Anlık Yanıt
                        </span>
                        <h2 class="text-3xl font-black text-afiDark mb-3">WhatsApp ile Ulaşın</h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">
                            Anlık destek, sipariş durumu takibi ve hızlı ürün sorularınız için WhatsApp hattımız üzerinden müşteri ekibimize 7/24 yazabilirsiniz.
                        </p>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 text-xs font-semibold text-gray-600">
                            <i class="fa-solid fa-circle-check text-green-500 text-sm"></i> Ortamala Yanıt Süresi: <span class="font-bold text-afiDark">5 Dakika</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-semibold text-gray-600">
                            <i class="fa-regular fa-clock text-yellow-500 text-sm"></i> Mesai Saatleri: <span class="font-bold text-afiDark">Pzt - Cmt: 09:00 - 19:00</span>
                        </div>
                        <a href="https://wa.me/905555555555" target="_blank" class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-4 rounded-2xl text-center shadow-lg shadow-green-500/20 transition-all duration-300 flex items-center justify-center gap-3 mt-4 text-base">
                            <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp Sohbeti Başlat
                        </a>
                    </div>
                </div>

                <!-- Office / Technical Service Info Card -->
                <div class="bg-afiDark text-white rounded-3xl p-8 shadow-xl border-2 border-yellow-500/30 relative overflow-hidden flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-500 text-afiDark flex items-center justify-center text-2xl shrink-0 font-black shadow-lg">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-base text-white">Teknik Servis & Mağaza</h4>
                        <p class="text-xs text-gray-400 mt-1">İkinci el test merkezi & garantili donanım servisi.</p>
                    </div>
                </div>

            </div>

            <!-- Right Column: Interactive Direct Email / Contact Form Card (7 Cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100 relative overflow-hidden flex flex-col justify-between">
                
                <div>
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-3xl shrink-0 border border-yellow-200 shadow-sm">
                            <i class="fa-regular fa-paper-plane"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black text-afiDark">E-Posta / İletişim Formu</h2>
                            <p class="text-gray-500 text-xs md:text-sm mt-1">Outlook veya e-posta uygulaması açmadan doğrudan bu form üzerinden bize yazabilirsiniz.</p>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-5 rounded-2xl mb-8 font-bold text-center flex items-center justify-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-2xl text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl mb-8 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Ad Soyad <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Adınız ve Soyadınız" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 pl-11 text-afiDark focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:outline-none transition text-sm">
                                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">E-Posta Adresi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="ornek@domain.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 pl-11 text-afiDark focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:outline-none transition text-sm">
                                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konu / Başlık <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="subject" required value="{{ old('subject') }}" placeholder="Mesajınızın konusu nedir?" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 pl-11 text-afiDark focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:outline-none transition text-sm">
                                <i class="fa-solid fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mesajınız / Probleminiz <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <textarea name="message" required rows="5" placeholder="Bize iletmek istediğiniz mesajınızı veya yaşadığınız problemi yazabilirsiniz..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-afiDark focus:ring-2 focus:ring-yellow-400 focus:bg-white focus:outline-none transition leading-relaxed text-sm">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-afiDark hover:bg-yellow-500 hover:text-afiDark text-white font-black py-4 rounded-xl text-base transition-all duration-300 shadow-xl shadow-afiDark/10 flex items-center justify-center gap-3 group mt-2">
                            <i class="fa-solid fa-paper-plane group-hover:translate-x-1 transition-transform"></i> Mesajı Gönder
                        </button>
                    </form>
                </div>

            </div>

        </div>
        
        <!-- Harita ve Adres Kartı -->
        <div class="max-w-7xl mx-auto bg-white rounded-3xl p-4 shadow-sm border border-gray-100 relative overflow-hidden h-[400px] group flex items-center justify-center">
            <!-- Harita Arka Planı (Placeholder) -->
            <div class="absolute inset-0 bg-gray-200" style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1600&q=80'); background-size: cover; background-position: center; opacity: 0.5;">
            </div>
            
            <!-- Adres Kartı -->
            <div class="relative z-10 bg-afiDark/90 backdrop-blur-md p-10 rounded-2xl text-center shadow-2xl border border-gray-800 transform group-hover:scale-105 transition-transform duration-500">
                <div class="w-16 h-16 mx-auto bg-yellow-500 text-afiDark rounded-full flex items-center justify-center text-2xl mb-6 shadow-lg">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h3 class="text-3xl font-black text-white mb-2">Afi Bilişim Merkez</h3>
                <p class="text-gray-300 text-lg max-w-md mx-auto mb-6">
                    Teknoloji Mahallesi, Bilişim Caddesi No:1<br>Merkez / Türkiye
                </p>
                <button type="button" class="px-8 py-3 bg-yellow-500 text-afiDark font-bold rounded-xl hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/20 flex items-center gap-2 mx-auto">
                    <i class="fa-solid fa-map"></i> Yol Tarifi Al
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
