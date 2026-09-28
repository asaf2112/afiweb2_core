{{--
    ╔══════════════════════════════════════════════════════╗
    ║  AFI BİLİŞİM — Global Loader Bileşeni               ║
    ║                                                      ║
    ║  Kullanım:                                           ║
    ║    @include('components.loader')         → Overlay   ║
    ║    @include('components.loader', ['type' => 'inline'])  → Küçük ║
    ║    @include('components.loader', ['type' => 'card'])    → Kart  ║
    ╚══════════════════════════════════════════════════════╝
--}}

@php $type = $type ?? 'overlay'; @endphp

@if($type === 'overlay')
{{-- ── Full-Page Overlay Loader ── --}}
<div id="afi-loader-overlay"
     aria-label="Yükleniyor"
     role="status"
     style="display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(17,17,17,0.82); backdrop-filter:blur(6px);
            -webkit-backdrop-filter:blur(6px);
            align-items:center; justify-content:center; flex-direction:column; gap:20px;">

    {{-- Halka animasyonu --}}
    <div style="position:relative; width:72px; height:72px;">
        {{-- Dış sabit halka --}}
        <div style="position:absolute; inset:0; border-radius:50%;
                    border:3px solid rgba(255,179,0,0.15);"></div>
        {{-- Dönen sarı segment --}}
        <div class="afi-spin-ring" style="position:absolute; inset:0; border-radius:50%;
                    border:3px solid transparent;
                    border-top-color:#FFB300;
                    border-right-color:#FFB300;
                    animation:afiSpin .9s cubic-bezier(0.5,0,0.5,1) infinite;"></div>
        {{-- İç nokta --}}
        <div style="position:absolute; inset:22px; border-radius:50%;
                    background:rgba(255,179,0,0.12);
                    display:flex; align-items:center; justify-content:center;">
            <div style="width:10px; height:10px; border-radius:50%;
                        background:#FFB300;
                        animation:afiPulse 1.4s ease-in-out infinite;"></div>
        </div>
    </div>

    {{-- Metin --}}
    <p style="color:#FFB300; font-family:'Outfit',sans-serif; font-weight:700;
               font-size:13px; letter-spacing:0.08em; text-transform:uppercase;
               animation:afiPulse 1.4s ease-in-out infinite; opacity:0.9;">
        {{ $message ?? 'Yükleniyor...' }}
    </p>
</div>

@elseif($type === 'inline')
{{-- ── Küçük Inline Spinner (buton içi veya form loader) ── --}}
<span class="afi-inline-loader"
      style="display:inline-flex; align-items:center; gap:8px; vertical-align:middle;">
    <svg width="18" height="18" viewBox="0 0 18 18" style="animation:afiSpin .8s linear infinite; flex-shrink:0;">
        <circle cx="9" cy="9" r="7" fill="none" stroke="rgba(255,179,0,0.25)" stroke-width="2.5"/>
        <path d="M9 2 A7 7 0 0 1 16 9" fill="none" stroke="#FFB300" stroke-width="2.5" stroke-linecap="round"/>
    </svg>
    @if(isset($message))
        <span style="font-size:13px; color:#FFB300; font-weight:600;">{{ $message }}</span>
    @endif
</span>

@elseif($type === 'card')
{{-- ── Ürün Grid / Liste Alanı İçin Kart Loader ── --}}
<div id="afi-card-loader"
     style="display:none; position:absolute; inset:0; z-index:50;
            background:rgba(248,249,250,0.88); backdrop-filter:blur(3px);
            border-radius:inherit;
            display:none; align-items:center; justify-content:center; flex-direction:column; gap:14px;
            pointer-events:all;">

    <div style="position:relative; width:52px; height:52px;">
        <div style="position:absolute; inset:0; border-radius:50%;
                    border:2.5px solid rgba(16,185,129,0.15);"></div>
        <div style="position:absolute; inset:0; border-radius:50%;
                    border:2.5px solid transparent;
                    border-top-color:#10B981;
                    border-right-color:#10B981;
                    animation:afiSpin .85s cubic-bezier(0.5,0,0.5,1) infinite;"></div>
        <div style="position:absolute; inset:16px; border-radius:50%;
                    background:rgba(16,185,129,0.1);
                    display:flex; align-items:center; justify-content:center;">
            <div style="width:7px; height:7px; border-radius:50%;
                        background:#10B981;
                        animation:afiPulse 1.2s ease-in-out infinite;"></div>
        </div>
    </div>
    <p style="color:#10B981; font-size:12px; font-weight:700;
               letter-spacing:0.06em; text-transform:uppercase;
               animation:afiPulse 1.2s ease-in-out infinite;">
        {{ $message ?? 'Filtreleniyor...' }}
    </p>
</div>
@endif
