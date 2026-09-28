@extends('admin.layouts.app')

@section('title', 'Yeni Slider Ekle | Admin Panel')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-slate-800/80 p-6 rounded-2xl border border-slate-700/80 backdrop-blur-md">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-3">
                <i class="fa-solid fa-plus-circle text-adminYellow"></i> Yeni Slider / Slayt Ekle
            </h1>
            <p class="text-sm text-slate-400 mt-1">Anasayfa carousel alanı için yeni bir kampanya slaytı oluşturun.</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Listeye Dön
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Bölüm: Slayt Ana İçeriği -->
        <div class="bg-slate-800/60 border border-slate-700/80 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-700/80 pb-3">
                <i class="fa-solid fa-heading text-adminYellow"></i> Ana Başlık ve Açıklama
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Ana Başlık (Metin) *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="ör: Oyuncu Bilgisayarları &" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-adminYellow">
                    @error('title') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Vurgulu Alt Başlık (Sarı/Renkli)</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="ör: Hazır Sistem İndirimleri" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-adminYellow">
                    @error('subtitle') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Slayt Açıklama Metni</label>
                <textarea name="description" rows="3" placeholder="Slaytın detay metnini girin..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-adminYellow">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- 2. Bölüm: Üst Rozet & Özellik Etiketleri -->
        <div class="bg-slate-800/60 border border-slate-700/80 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-700/80 pb-3">
                <i class="fa-solid fa-tags text-adminYellow"></i> Üst Rozet & Özellik Tagleri
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Üst Rozet İkonu</label>
                    <input type="text" name="top_badge_icon" value="{{ old('top_badge_icon', 'fa-gamepad') }}" placeholder="fa-gamepad, fa-bolt, fa-fire" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-adminYellow">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Üst Rozet Metni</label>
                    <input type="text" name="top_badge_text" value="{{ old('top_badge_text') }}" placeholder="🎮 HAZIR SİSTEM KAMPANYASI" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-adminYellow">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Üst Rozet Teması *</label>
                    <select name="top_badge_color" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-adminYellow">
                        <option value="amber" {{ old('top_badge_color') == 'amber' ? 'selected' : '' }}>Amber (Sarı/Turuncu)</option>
                        <option value="cyan" {{ old('top_badge_color') == 'cyan' ? 'selected' : '' }}>Cyan (Mavi/Yeşil)</option>
                        <option value="blue" {{ old('top_badge_color') == 'blue' ? 'selected' : '' }}>Blue (Koyu Mavi)</option>
                        <option value="red" {{ old('top_badge_color') == 'red' ? 'selected' : '' }}>Red (Kırmızı)</option>
                        <option value="emerald" {{ old('top_badge_color') == 'emerald' ? 'selected' : '' }}>Emerald (Yeşil)</option>
                        <option value="purple" {{ old('top_badge_color') == 'purple' ? 'selected' : '' }}>Purple (Mor)</option>
                    </select>
                </div>
            </div>

            <!-- 3 Etiket Özellikleri -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="space-y-2 bg-slate-900/60 p-3 rounded-xl border border-slate-700/50">
                    <span class="text-xs font-bold text-amber-400">1. Özellik Rozeti</span>
                    <input type="text" name="tag1_icon" value="{{ old('tag1_icon', 'fa-solid fa-check') }}" placeholder="fa-solid fa-gamepad" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                    <input type="text" name="tag1_text" value="{{ old('tag1_text') }}" placeholder="Ultra FPS Performansı" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-2 bg-slate-900/60 p-3 rounded-xl border border-slate-700/50">
                    <span class="text-xs font-bold text-amber-400">2. Özellik Rozeti</span>
                    <input type="text" name="tag2_icon" value="{{ old('tag2_icon', 'fa-solid fa-shield-halved') }}" placeholder="fa-solid fa-shield-halved" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                    <input type="text" name="tag2_text" value="{{ old('tag2_text') }}" placeholder="3 Yıl Parça Garantisi" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
                <div class="space-y-2 bg-slate-900/60 p-3 rounded-xl border border-slate-700/50">
                    <span class="text-xs font-bold text-amber-400">3. Özellik Rozeti</span>
                    <input type="text" name="tag3_icon" value="{{ old('tag3_icon', 'fa-solid fa-truck-fast') }}" placeholder="fa-solid fa-truck-fast" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                    <input type="text" name="tag3_text" value="{{ old('tag3_text') }}" placeholder="Aynı Gün Kargo" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- 3. Bölüm: Butonlar & Linkler -->
        <div class="bg-slate-800/60 border border-slate-700/80 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-700/80 pb-3">
                <i class="fa-solid fa-link text-adminYellow"></i> Ana Buton Yönlendirmesi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Sol Ana Buton Yazısı *</label>
                    <input type="text" name="button_text" value="{{ old('button_text', 'Keşfet') }}" required placeholder="ör: Hazır Sistemleri Keşfet" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-adminYellow">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Sol Ana Buton Linki (URL)</label>
                    <input type="text" name="button_url" value="{{ old('button_url') }}" placeholder="/urunler?category=masaustu-bilgisayar" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-adminYellow">
                </div>
            </div>
        </div>

        <!-- 4. Bölüm: Sağ Ürün Kartı & Özel "Ürüne Git" Butonu -->
        <div class="bg-slate-800/60 border border-amber-500/30 rounded-2xl p-6 space-y-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-amber-500 text-slate-950 font-black text-[10px] px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                Sağ Ürün Vitrini
            </div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-700/80 pb-3">
                <i class="fa-solid fa-box-open text-amber-400"></i> Sağ Taraf Dekoratif Ürün Kartı & Direkt Ürüne Git Butonu
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kart Üst Başlık</label>
                    <input type="text" name="card_header" value="{{ old('card_header', 'AFI GAMING PC') }}" placeholder="AFI GAMING PC" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kart İndirim/Özellik Rozeti</label>
                    <input type="text" name="card_badge" value="{{ old('card_badge', '%25 İNDİRİM') }}" placeholder="%25 İNDİRİM" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Kart Ürün Adı</label>
                    <input type="text" name="card_title" value="{{ old('card_title') }}" placeholder="Intel i7 14700F + RTX 4070 Ti SUPER" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Özellik 1</label>
                    <input type="text" name="card_spec1" value="{{ old('card_spec1') }}" placeholder="• 32GB DDR5 RAM" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Özellik 2</label>
                    <input type="text" name="card_spec2" value="{{ old('card_spec2') }}" placeholder="• 1TB NVMe M.2 SSD" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Özellik 3</label>
                    <input type="text" name="card_spec3" value="{{ old('card_spec3') }}" placeholder="• 240mm Sıvı Soğutmalı" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Eski Fiyat (Üstü Çizili)</label>
                    <input type="text" name="card_old_price" value="{{ old('card_old_price') }}" placeholder="64.999 ₺" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Güncel İndirimli Fiyat</label>
                    <input type="text" name="card_price" value="{{ old('card_price') }}" placeholder="48.999 ₺" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-amber-400 font-bold">
                </div>
            </div>

            <!-- KULLANICI İSTEĞİ: ÜRÜN KARTINA BUTON & URL -->
            <div class="bg-amber-500/10 border border-amber-500/30 p-4 rounded-xl space-y-3">
                <div class="flex items-center gap-2 text-amber-400 font-bold text-xs">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Sağ Ürün Kartına Özel "Ürüne Git" Butonu
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Buton Üzerindeki Yazı</label>
                        <input type="text" name="card_button_text" value="{{ old('card_button_text', 'Ürüne Git') }}" placeholder="Ürüne Git" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Direkt Ürün Detay Linki (URL)</label>
                        <input type="text" name="card_button_url" value="{{ old('card_button_url') }}" placeholder="/urun-detay/rtx-4070-ti-super" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Bölüm: Tasarım, Sıralama & Görsel -->
        <div class="bg-slate-800/60 border border-slate-700/80 rounded-2xl p-6 space-y-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-700/80 pb-3">
                <i class="fa-solid fa-palette text-adminYellow"></i> Görsel Stil, Arka Plan & Sıralama
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Glow Işık Rengi *</label>
                    <select name="glow_color" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-adminYellow">
                        <option value="amber" {{ old('glow_color') == 'amber' ? 'selected' : '' }}>Amber (Sarı Işıma)</option>
                        <option value="cyan" {{ old('glow_color') == 'cyan' ? 'selected' : '' }}>Cyan (Mavi/Açık Yeşil Işıma)</option>
                        <option value="blue" {{ old('glow_color') == 'blue' ? 'selected' : '' }}>Blue (Koyu Mavi Işıma)</option>
                        <option value="red" {{ old('glow_color') == 'red' ? 'selected' : '' }}>Red (Kırmızı Işıma)</option>
                        <option value="emerald" {{ old('glow_color') == 'emerald' ? 'selected' : '' }}>Emerald (Yeşil Işıma)</option>
                        <option value="purple" {{ old('glow_color') == 'purple' ? 'selected' : '' }}>Purple (Mor Işıma)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Arka Plan Filigran Metni</label>
                    <input type="text" name="watermark_text" value="{{ old('watermark_text', 'GAMING') }}" placeholder="GAMING, NVMe M.2, GPU RTX" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Görsel (Opsiyonel)</label>
                    <input type="file" name="image" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-400">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Arka Plan Gradyanı (CSS Class)</label>
                    <input type="text" name="bg_gradient" value="{{ old('bg_gradient', 'from-[#1a1103] via-[#140d02] to-[#090b10]') }}" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono">
                </div>
                <div class="flex items-center gap-6 pt-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Sıralama Sırası</label>
                        <input type="number" name="order" value="{{ old('order', 1) }}" min="0" class="w-24 bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white">
                    </div>
                    <div class="flex items-center gap-2 pt-4">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 accent-yellow-400 rounded">
                        <label for="is_active" class="text-sm font-bold text-white cursor-pointer">Slayt Aktif Olsun</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-3 rounded-xl bg-slate-700 text-slate-300 hover:text-white font-bold text-sm">İptal</a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-adminYellow hover:bg-yellow-400 text-slate-950 font-black text-sm shadow-xl shadow-yellow-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-check"></i> Slaytı Kaydet ve Yayınla
            </button>
        </div>
    </form>
</div>
@endsection
