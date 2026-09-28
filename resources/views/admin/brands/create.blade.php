@extends('admin.layouts.app')

@section('title', 'Yeni Marka Ekle | Afi Bilişim Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.brands.index') }}" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-700">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <h1 class="text-2xl font-black text-white tracking-tight">Yeni Marka Ekle</h1>
            </div>
            <p class="text-slate-400 text-sm mt-1 ml-12">HP, TwinMOS veya yeni bir donanım markasını sisteme dahil edin.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Lütfen formdaki hataları kontrol edin:
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-adminCard p-6 rounded-2xl border border-adminBorder shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center gap-2 pb-3 border-b border-adminBorder">
                <i class="fa-solid fa-circle-info text-yellow-400"></i> Temel Marka Bilgileri
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Marka Adı -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Marka Adı <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Örn: HP, TwinMOS, Razer..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
                    <p class="text-[11px] text-slate-500 mt-1">Ürünlerde ve filtrelerde görünecek marka adı.</p>
                </div>

                <!-- Slug (URL) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Özel Slug (İsteğe Bağlı)
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Örn: hp, twinmos (boş bırakılırsa otomatik üretilir)" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
                    <p class="text-[11px] text-slate-500 mt-1">/marka/{slug} adresinde kullanılır.</p>
                </div>

                <!-- Slogan -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Slogan / Kısa Başlık
                    </label>
                    <input type="text" name="slogan" value="{{ old('slogan') }}" placeholder="Örn: Yüksek Performanslı Bellek & SSD Teknolojileri" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
                </div>

                <!-- Rozet / Badge -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Öne Çıkan Rozet (Badge)
                    </label>
                    <input type="text" name="badge" value="{{ old('badge') }}" placeholder="Örn: Global Dev, Hafıza & SSD, Gaming" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
                </div>
            </div>

            <!-- Tanıtım Metni (Description) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                    Marka Tanıtım & Biyografi Metni
                </label>
                <textarea name="description" rows="4" placeholder="Marka hakkında özet tanıtım metni yazın (marka kataloğunun üst kısmında görünür)..." class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition leading-relaxed">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Logo & Görsel Ayarları -->
        <div class="bg-adminCard p-6 rounded-2xl border border-adminBorder shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center gap-2 pb-3 border-b border-adminBorder">
                <i class="fa-solid fa-image text-yellow-400"></i> Marka Logosu
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Logo Dosyası Yükleme -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Logo Dosyası Yükle (SVG, PNG, WEBP, JPG)
                    </label>
                    <input type="file" name="logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-yellow-500/20 file:text-yellow-400 hover:file:bg-yellow-500/30 cursor-pointer">
                    <p class="text-[11px] text-slate-500 mt-1">Şeffaf arka planlı SVG veya PNG önerilir.</p>
                </div>

                <!-- Logo Harici URL -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        VEYA Harici Logo URL'i
                    </label>
                    <input type="url" name="logo_url" value="{{ old('logo_url') }}" placeholder="https://upload.wikimedia.org/.../logo.svg" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-adminYellow transition">
                    <p class="text-[11px] text-slate-500 mt-1">CDN veya Wikipedia SVG direkt linki verebilirsiniz.</p>
                </div>
            </div>
        </div>

        <!-- Yayın ve Vitrin Ayarları -->
        <div class="bg-adminCard p-6 rounded-2xl border border-adminBorder shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center gap-2 pb-3 border-b border-adminBorder">
                <i class="fa-solid fa-sliders text-yellow-400"></i> Yayın & Sıralama
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Sıralama Önceliği -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Sıralama Değeri (Sort Order)
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-adminYellow transition">
                    <p class="text-[11px] text-slate-500 mt-1">Küçük sayılar daha önce listelenir.</p>
                </div>

                <!-- Vitrin Durumu -->
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-5 h-5 rounded bg-slate-900 border-slate-700 text-yellow-500 focus:ring-0 focus:ring-offset-0">
                        <div>
                            <span class="text-sm font-bold text-white block">Ana Sayfa Vitrininde Göster</span>
                            <span class="text-xs text-slate-400">"Popüler Marka Katalogları" alanında kart olarak çıkar.</span>
                        </div>
                    </label>
                </div>

                <!-- Aktiflik Durumu -->
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-5 h-5 rounded bg-slate-900 border-slate-700 text-emerald-500 focus:ring-0 focus:ring-offset-0">
                        <div>
                            <span class="text-sm font-bold text-white block">Marka Aktif</span>
                            <span class="text-xs text-slate-400">İşaret kaldırılırsa sitede gizlenir.</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.brands.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold transition text-sm">
                Vazgeç
            </a>
            <button type="submit" class="bg-adminYellow hover:bg-yellow-400 text-slate-900 font-black py-2.5 px-7 rounded-xl transition shadow-lg shadow-yellow-500/20 text-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-check"></i> Markayı Kaydet
            </button>
        </div>
    </form>

</div>
@endsection