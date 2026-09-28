@extends('layouts.app')

@section('title', 'Profilim | Afi Bilişim')

@section('content')
<div class="min-h-screen bg-afiGray py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Başlık -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black font-heading tracking-tighter text-afiDark">PROFİLİM</h1>
                <p class="text-gray-500 mt-1">Hesap ayarlarınızı, siparişlerinizi ve favorilerinizi buradan yönetebilirsiniz.</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-500">Hoş Geldiniz,</p>
                <p class="text-xl font-black text-adminAccent">{{ $user->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sol Menü -->
            <div class="lg:col-span-1 space-y-2">
                <button onclick="switchTab('settings')" id="btn-settings" class="w-full flex items-center gap-3 px-6 py-4 rounded-xl font-bold transition-all bg-afiDark text-white shadow-lg">
                    <i class="fa-solid fa-user-gear"></i> Hesap Ayarları
                </button>
                <a href="{{ route('orders.index') }}" class="w-full flex items-center gap-3 px-6 py-4 rounded-xl font-bold transition-all bg-white text-gray-600 hover:bg-gray-50 hover:text-afiDark border border-gray-100">
                    <i class="fa-solid fa-box"></i> Siparişlerim <span class="ml-auto bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md text-xs">{{ count($orders) }}</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="w-full flex items-center gap-3 px-6 py-4 rounded-xl font-bold transition-all bg-white text-gray-600 hover:bg-gray-50 hover:text-afiDark border border-gray-100">
                    <i class="fa-solid fa-heart"></i> Favorilerim
                </a>
                <a href="{{ route('profile.notifications') }}" class="w-full flex items-center gap-3 px-6 py-4 rounded-xl font-bold transition-all bg-white text-gray-600 hover:bg-gray-50 hover:text-afiDark border border-gray-100">
                    <i class="fa-solid fa-bell"></i> Bildirimlerim
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-4 mt-4 border-t border-gray-200">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 rounded-xl font-bold transition-all bg-red-50 text-red-600 hover:bg-red-100 border border-red-100">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Çıkış Yap
                    </button>
                </form>
            </div>

            <!-- Sağ İçerik -->
            <div class="lg:col-span-3">
                <!-- Hesap Ayarları Tabı -->
                <div id="tab-settings" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-afiDark mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-adminAccent"></i> İletişim Bilgilerimi Tamamla
                    </h2>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Ad Soyad</label>
                                <input type="text" value="{{ $user->name }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed" disabled>
                            </div>
                            
                            @if(empty($user->email))
                            <div>
                                <label class="block text-sm font-bold text-red-600 mb-2">
                                    <i class="fa-solid fa-triangle-exclamation"></i> E-Posta Adresi (Eksik)
                                </label>
                                <input type="email" name="email" class="w-full px-4 py-3 border border-red-300 rounded-xl focus:ring-2 focus:ring-adminAccent focus:outline-none transition bg-red-50" placeholder="E-posta adresinizi ekleyin">
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">E-Posta Adresi</label>
                                <input type="email" value="{{ $user->email }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed" disabled>
                            </div>
                            @endif
                            
                            @if(empty($user->phone))
                            <div>
                                <label class="block text-sm font-bold text-red-600 mb-2">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Telefon Numarası (Eksik)
                                </label>
                                <input type="text" name="phone" id="phone" class="w-full px-4 py-3 border border-red-300 rounded-xl focus:ring-2 focus:ring-adminAccent focus:outline-none transition bg-red-50" placeholder="05XX XXX XX XX">
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Telefon Numarası</label>
                                <input type="text" value="{{ $user->phone }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 cursor-not-allowed" disabled>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-gradient-btn text-afiDark font-bold py-3 px-8 rounded-xl hover:shadow-lg transition flex items-center gap-2">
                                <i class="fa-solid fa-save"></i> Bilgileri Güncelle
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // İçerikleri Gizle
        document.getElementById('tab-settings').classList.add('hidden');
        
        // Aktif İçeriği Göster
        document.getElementById('tab-' + tabName).classList.remove('hidden');

        // Buton Stillerini Sıfırla
        const buttons = ['settings'];
        buttons.forEach(btn => {
            const el = document.getElementById('btn-' + btn);
            el.classList.remove('bg-afiDark', 'text-white', 'shadow-lg');
            el.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        });

        // Aktif Buton Stilini Ayarla
        const activeEl = document.getElementById('btn-' + tabName);
        activeEl.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
        activeEl.classList.add('bg-afiDark', 'text-white', 'shadow-lg');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
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
        }
    });
</script>
@endsection
