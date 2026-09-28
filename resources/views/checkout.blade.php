@extends('layouts.app')

@section('title', 'Güvenli Ödeme | Afi Bilişim')

@section('content')
<style>
/* ── Kart Flip ── */
.pay-scene { perspective: 1000px; }
.pay-card  { width: 340px; height: 210px; position: relative; transform-style: preserve-3d; transition: transform .7s cubic-bezier(.4,0,.2,1); }
.pay-card.flipped { transform: rotateY(180deg); }
.pay-face  { position: absolute; inset: 0; border-radius: 20px; backface-visibility: hidden; -webkit-backface-visibility: hidden; display: flex; align-items: center; justify-content: center; flex-direction: column; }
.pay-front { background: linear-gradient(135deg, #1E1E1E 0%, #111 100%); border: 1px solid rgba(255,179,0,.25); }
.pay-back  { background: linear-gradient(135deg, #064E3B 0%, #065F46 100%); transform: rotateY(180deg); border: 1px solid rgba(16,185,129,.35); }

/* ── Spinner ring ── */
@keyframes payRing { to { transform: rotate(360deg); } }
.pay-ring { width: 56px; height: 56px; border-radius: 50%; border: 3px solid rgba(255,179,0,.18); border-top-color: #FFB300; animation: payRing .8s linear infinite; }

/* ── Pulse dot ── */
@keyframes payDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.82)} }
.pay-dot { width: 10px; height: 10px; border-radius: 50%; background: #FFB300; box-shadow: 0 0 12px 4px rgba(255,179,0,.5); animation: payDot 1.4s ease-in-out infinite; }

/* ── Success tick draw ── */
@keyframes tickDraw { to { stroke-dashoffset: 0; } }
.tick-path { stroke-dasharray: 80; stroke-dashoffset: 80; animation: tickDraw .55s .35s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes circleDraw { to { stroke-dashoffset: 0; } }
.circle-path { stroke-dasharray: 210; stroke-dashoffset: 210; animation: circleDraw .5s cubic-bezier(.4,0,.2,1) forwards; }
@keyframes successGlow { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,.5)} 50%{box-shadow:0 0 0 18px rgba(16,185,129,0)} }
.success-ring { animation: successGlow 1.6s ease-in-out infinite; }

/* ── Shake error ── */
@keyframes shake { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-8px)} 40%,80%{transform:translateX(8px)} }
.shake { animation: shake .45s ease; }

/* ── Fade/slide transitions ── */
.state-enter { animation: stateIn .45s cubic-bezier(.4,0,.2,1) both; }
@keyframes stateIn { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* ── Scanning line on card ── */
@keyframes scan { 0%{top:20%} 100%{top:80%} }
.scan-line { position:absolute; left:0; right:0; height:2px; background:linear-gradient(90deg,transparent,rgba(255,179,0,.6),transparent); animation: scan 1.8s ease-in-out infinite alternate; }

/* ── Shimmer on card ── */
@keyframes shimmer { 0%{left:-100%} 100%{left:200%} }
.card-shimmer { position:absolute; inset:0; border-radius:20px; overflow:hidden; pointer-events:none; }
.card-shimmer::after { content:''; position:absolute; top:0; bottom:0; width:50%; background:linear-gradient(90deg,transparent,rgba(255,255,255,.06),transparent); animation:shimmer 2.2s linear infinite; }
</style>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
     x-data="paymentApp()"
     x-init="init()">

    {{-- ── Başlık ── --}}
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black font-heading tracking-tighter text-afiDark">GÜVENLİ ÖDEME</h1>
                <p class="text-gray-500 mt-1">Kart bilgileriniz 256-bit SSL ile şifrelenerek korunmaktadır.</p>
            </div>
            <a href="{{ route('cart.index') }}"
               x-show="state === 'form'"
               class="bg-white border border-gray-200 text-gray-700 font-bold py-2 px-6 rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Sepete Dön
            </a>
        </div>

        {{-- Hata mesajı (normal submit fallback) --}}
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8 flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ════ SOL PANEL ════ --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative min-h-[480px] flex flex-col">

                    {{-- ── STATE: FORM ── --}}
                    <div x-show="state === 'form'" x-transition:enter="state-enter" class="p-8 flex-1">
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-afiDark flex items-center gap-2">
                                <i class="fa-regular fa-credit-card text-yellow-500"></i> Kredi / Banka Kartı
                            </h2>
                            <div class="flex gap-2 text-2xl text-gray-400">
                                <i class="fa-brands fa-cc-visa text-blue-800"></i>
                                <i class="fa-brands fa-cc-mastercard text-red-600"></i>
                            </div>
                        </div>

                        {{-- Inline hata bandı --}}
                        <div x-show="errorMsg" x-transition
                             :class="{'shake': shaking}"
                             class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm font-semibold">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span x-text="errorMsg"></span>
                        </div>

                        <form id="checkout-form" @submit.prevent="submitPayment" novalidate>
                            @csrf
                            {{-- Kart İsmi --}}
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Kart Üzerindeki İsim</label>
                                <input type="text" name="card_name" x-model="form.card_name"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all outline-none font-medium"
                                       placeholder="Ad Soyad" required>
                            </div>
                            {{-- Kart No --}}
                            <div class="mb-6 relative">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Kart Numarası</label>
                                <input type="text" name="card_number" x-model="form.card_number"
                                       @input="formatCardNumber($event)"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all outline-none font-medium tracking-widest"
                                       placeholder="0000 0000 0000 0000" maxlength="19" required>
                                <div class="absolute right-4 top-[42px] text-gray-400"><i class="fa-solid fa-credit-card"></i></div>
                            </div>
                            {{-- Son Kullanma + CVV --}}
                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Son Kullanma</label>
                                    <div class="flex gap-2">
                                        <select name="card_month" x-model="form.card_month"
                                                class="w-1/2 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none font-medium appearance-none" required>
                                            <option value="" disabled selected>Ay</option>
                                            @for($i=1; $i<=12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                        <select name="card_year" x-model="form.card_year"
                                                class="w-1/2 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none font-medium appearance-none" required>
                                            <option value="" disabled selected>Yıl</option>
                                            @for($i=date('y'); $i<=date('y')+15; $i++)
                                            <option value="{{ $i }}">20{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide flex justify-between">
                                        CVV <i class="fa-regular fa-circle-question text-gray-400 cursor-help" title="Kartınızın arkasındaki 3 haneli güvenlik kodu."></i>
                                    </label>
                                    <input type="password" name="cvv" x-model="form.cvv"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all outline-none font-medium tracking-widest"
                                           placeholder="•••" maxlength="4"
                                           @input="form.cvv = form.cvv.replace(/[^0-9]/g,'')" required>
                                </div>
                            </div>

                            {{-- Güvenlik notu --}}
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3 mb-8">
                                <i class="fa-solid fa-shield-halved text-yellow-600 mt-1"></i>
                                <p class="text-sm text-yellow-800 font-medium">Ödemeniz 3D Secure güvencesiyle bankanız tarafından doğrulanacaktır. Tutar hemen çekilmez.</p>
                            </div>

                            <button type="submit" id="pay-submit-btn"
                                    class="w-full bg-afiDark hover:bg-gray-800 text-white font-bold py-4 rounded-xl text-lg transition-colors shadow-lg shadow-gray-900/20 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-lock"></i>
                                {{ number_format($total, 2) }} ₺ — Ödemeyi Tamamla
                            </button>
                        </form>
                    </div>

                    {{-- ── STATE: PROCESSING ── --}}
                    <div x-show="state === 'processing'" x-transition:enter="state-enter"
                         class="flex-1 flex flex-col items-center justify-center py-16 px-8 bg-gradient-to-br from-[#0d1117] to-[#111827]">

                        {{-- Kredi Kartı simgesi --}}
                        <div class="pay-scene mb-10">
                            <div class="pay-card" :class="{'flipped': cardFlipped}">
                                {{-- Ön yüz --}}
                                <div class="pay-face pay-front">
                                    <div class="card-shimmer"></div>
                                    <div class="scan-line"></div>
                                    {{-- Çip --}}
                                    <div class="absolute top-6 left-6 w-10 h-8 rounded-md bg-gradient-to-br from-yellow-400 to-yellow-600 opacity-90"></div>
                                    {{-- Logo --}}
                                    <div class="absolute top-5 right-6 text-white font-heading font-black text-lg tracking-tight">
                                        AFI<span class="text-yellow-400">PAY</span>
                                    </div>
                                    {{-- Dönen halka --}}
                                    <div class="mt-4 flex flex-col items-center gap-3">
                                        <div class="pay-ring"></div>
                                        <div class="pay-dot"></div>
                                    </div>
                                    {{-- Kart no blur --}}
                                    <div class="absolute bottom-10 left-6 text-yellow-400/60 font-mono text-sm tracking-[.25em]">•••• •••• •••• ••••</div>
                                    {{-- Bant --}}
                                    <div class="absolute bottom-4 left-6 right-6 flex justify-between items-center">
                                        <span class="text-white/40 text-xs uppercase tracking-wider">Güvenli İşlem</span>
                                        <div class="flex gap-1.5 text-lg text-white/40">
                                            <i class="fa-brands fa-cc-visa"></i>
                                            <i class="fa-brands fa-cc-mastercard"></i>
                                        </div>
                                    </div>
                                </div>
                                {{-- Arka yüz (başarı) --}}
                                <div class="pay-face pay-back">
                                    <svg width="70" height="70" viewBox="0 0 70 70" fill="none">
                                        <circle class="circle-path" cx="35" cy="35" r="33" stroke="#10B981" stroke-width="3" stroke-linecap="round"/>
                                        <path class="tick-path" d="M18 36 L30 48 L52 24" stroke="#10B981" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="text-emerald-300 font-bold text-sm mt-4 tracking-widest uppercase">Onaylandı</p>
                                </div>
                            </div>
                        </div>

                        {{-- Mesaj --}}
                        <div class="text-center">
                            <p class="text-yellow-400 font-heading font-black text-xl tracking-wide mb-2"
                               x-text="processingMsg"></p>
                            <p class="text-gray-500 text-sm">Lütfen sayfayı kapatmayın veya yenilemeyin.</p>
                        </div>

                        {{-- İlerleme çubuğu --}}
                        <div class="w-64 mt-8 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-yellow-500 to-yellow-400 rounded-full transition-all duration-700 ease-out"
                                 :style="'width:' + progress + '%'"></div>
                        </div>
                        <p class="text-gray-600 text-xs mt-2" x-text="progress + '%'"></p>
                    </div>

                    {{-- ── STATE: SUCCESS ── --}}
                    <div x-show="state === 'success'" x-transition:enter="state-enter"
                         class="flex-1 flex flex-col items-center justify-center py-16 px-8 bg-gradient-to-br from-[#022c22] to-[#064e3b]">

                        {{-- Yeşil tik --}}
                        <div class="success-ring w-28 h-28 rounded-full bg-emerald-500/15 border-2 border-emerald-500/40 flex items-center justify-center mb-8">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                                <circle class="circle-path" cx="40" cy="40" r="37" stroke="#10B981" stroke-width="3" stroke-linecap="round"/>
                                <path class="tick-path" d="M22 41 L34 53 L58 27" stroke="#10B981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <h2 class="text-3xl font-black font-heading text-white mb-2">Ödeme Başarılı!</h2>
                        <p class="text-emerald-400 font-semibold text-base mb-1">Payment Successful</p>

                        <div class="mt-6 bg-emerald-900/40 border border-emerald-500/30 rounded-2xl px-8 py-4 text-center">
                            <p class="text-emerald-400 text-xs uppercase tracking-widest mb-1 font-bold">Referans Kodu</p>
                            <p class="text-white font-mono font-bold text-base" x-text="referenceCode"></p>
                        </div>

                        <p class="text-gray-400 text-sm mt-6 mb-4">
                            <span x-text="redirectCountdown"></span> saniye içinde ana sayfaya yönlendiriliyorsunuz...
                        </p>

                        <a :href="redirectUrl"
                           class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-white font-bold px-8 py-3 rounded-xl transition-all transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-house"></i> Hemen Git
                        </a>
                    </div>

                </div>{{-- /card panel --}}
            </div>{{-- /left col --}}

            {{-- ════ SAĞ PANEL — Sipariş Özeti ════ --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-28">
                    <h2 class="text-xl font-bold text-afiDark mb-6 pb-4 border-b border-gray-100">Sipariş Özeti</h2>

                    <div class="max-h-64 overflow-y-auto mb-6 pr-2 space-y-4">
                        @foreach($cart as $item)
                        <div class="flex gap-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0">
                                @php
                                    $imgUrl = 'https://placehold.co/100x100?text=Gorsel';
                                    if ($item['image'] && file_exists(public_path($item['image'])))
                                        $imgUrl = asset($item['image']);
                                    elseif ($item['image'] && file_exists(public_path('storage/' . $item['image'])))
                                        $imgUrl = asset('storage/' . $item['image']);
                                @endphp
                                <img src="{{ $imgUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain p-1">
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-sm font-bold text-gray-800 line-clamp-2">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item['quantity'] }} Adet × {{ number_format($item['price'], 2) }} ₺</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 pt-6 border-t border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Ara Toplam</span>
                            <span class="font-bold">{{ number_format($total, 2) }} ₺</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Kargo</span>
                            <span class="text-green-600 font-bold">Ücretsiz</span>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-black text-afiDark">Ödenecek Tutar</span>
                            <span class="text-3xl font-black text-yellow-500">{{ number_format($total, 2) }} ₺</span>
                        </div>
                    </div>

                    {{-- Güven rozetleri --}}
                    <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-3">
                        @foreach([['fa-shield-halved','text-green-500','SSL Korumalı'],['fa-lock','text-blue-500','3D Secure'],['fa-rotate-left','text-yellow-500','14 Gün İade'],['fa-headset','text-purple-500','7/24 Destek']] as [$ico,$clr,$lbl])
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i class="fa-solid {{ $ico }} {{ $clr }}"></i> {{ $lbl }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>{{-- /flex row --}}
    </div>{{-- /max-w --}}
</div>{{-- /page --}}

@push('scripts')
<script>
function paymentApp() {
    return {
        state: 'form',          // form | processing | success
        errorMsg: '',
        shaking: false,
        cardFlipped: false,
        processingMsg: 'Güvenli İşlem Gerçekleştiriliyor...',
        progress: 0,
        referenceCode: '',
        redirectUrl: '{{ route("home") }}',
        redirectCountdown: 5,
        form: { card_name:'', card_number:'', card_month:'', card_year:'', cvv:'' },

        init() {
            // Sayfa görünürlük geçişlerinde loader gizle
            window.addEventListener('pageshow', () => {
                if (this.state === 'processing') this.state = 'form';
            });
        },

        formatCardNumber(e) {
            let v = e.target.value.replace(/\D/g,'').slice(0,16);
            e.target.value = v.replace(/(\d{4})/g,'$1 ').trim();
            this.form.card_number = e.target.value;
        },

        showError(msg) {
            this.errorMsg = msg;
            this.shaking = false;
            this.$nextTick(() => { this.shaking = true; });
            setTimeout(() => { this.shaking = false; }, 500);
            // Butonu sıfırla
            const btn = document.getElementById('pay-submit-btn');
            if (btn && window.AfiLoader) AfiLoader.btnReset(btn);
        },

        async submitPayment() {
            this.errorMsg = '';
            // Basit ön doğrulama
            if (!this.form.card_name.trim()) return this.showError('Kart üzerindeki ismi giriniz.');
            const num = this.form.card_number.replace(/\s/g,'');
            if (num.length !== 16) return this.showError('Kart numarası 16 haneli olmalıdır.');
            if (!this.form.card_month) return this.showError('Son kullanma ayını seçiniz.');
            if (!this.form.card_year)  return this.showError('Son kullanma yılını seçiniz.');
            if (this.form.cvv.length < 3) return this.showError('CVV en az 3 haneli olmalıdır.');

            // Butona spinner
            const btn = document.getElementById('pay-submit-btn');
            if (btn && window.AfiLoader) AfiLoader.btn(btn, 'İşleniyor...');

            // Processing ekranına geç
            this.state = 'processing';
            this.progress = 0;
            this.processingMsg = 'Güvenli Bağlantı Kuruluyor...';

            // İlerleme animasyonu
            const steps = [
                { pct: 30, msg: 'Güvenli Bağlantı Kuruluyor...',   delay: 600  },
                { pct: 55, msg: 'Kart Bilgileri Doğrulanıyor...',  delay: 1200 },
                { pct: 78, msg: '3D Secure Kontrolü Yapılıyor...', delay: 2000 },
                { pct: 92, msg: 'Banka Onayı Bekleniyor...',       delay: 2800 },
            ];
            steps.forEach(s => {
                setTimeout(() => {
                    this.progress = s.pct;
                    this.processingMsg = s.msg;
                }, s.delay);
            });

            // Formdaki CSRF token al
            const csrfToken = document.querySelector('#checkout-form input[name="_token"]').value;

            const body = new URLSearchParams({
                _token: csrfToken,
                card_name:   this.form.card_name,
                card_number: this.form.card_number,
                card_month:  this.form.card_month,
                card_year:   this.form.card_year,
                cvv:         this.form.cvv,
            });

            try {
                const res = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: body.toString()
                });

                const data = await res.json();

                if (!data.success) {
                    // Hata — form'a geri dön
                    this.state = 'form';
                    this.showError(data.message || 'Ödeme sırasında bir hata oluştu.');
                    return;
                }

                // Başarı: kart arka yüzüne dönsün, ardından success state
                this.progress = 100;
                this.processingMsg = 'Ödeme Onaylandı!';

                setTimeout(() => { this.cardFlipped = true; }, 400);

                setTimeout(() => {
                    this.referenceCode = data.reference || '';
                    this.redirectUrl   = data.redirect  || '{{ route("home") }}';
                    this.state = 'success';
                    this.startCountdown();
                }, 1600);

            } catch (err) {
                console.error('Checkout error:', err);
                this.state = 'form';
                this.showError('Sunucu bağlantısında hata oluştu. Lütfen tekrar deneyin.');
            }
        },

        startCountdown() {
            this.redirectCountdown = 5;
            const iv = setInterval(() => {
                this.redirectCountdown--;
                if (this.redirectCountdown <= 0) {
                    clearInterval(iv);
                    window.location.href = this.redirectUrl;
                }
            }, 1000);
        }
    };
}
</script>
@endpush
@endsection
