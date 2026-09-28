<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Doğrulama - Afi Bilişim</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        adminDark: '#111111',
                        adminAccent: '#FFB300'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-adminDark flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute w-[800px] h-[800px] bg-adminAccent rounded-full mix-blend-multiply filter blur-[150px] opacity-20 top-[-200px] right-[-200px] animate-pulse"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 relative z-10 mx-4 text-center">
        
        <div class="w-20 h-20 bg-yellow-50 text-adminAccent rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h2 class="text-2xl font-bold text-adminDark mb-2">İki Aşamalı Doğrulama</h2>
        <p class="text-gray-500 text-sm mb-6">Lütfen Google Authenticator uygulamanızdaki 6 haneli güvenlik kodunu girin.</p>

        @if(isset($errors) && $errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-xs font-semibold text-left">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.2fa.verify.post') }}" method="POST">
            @csrf
            <div class="mb-6">
                <input type="text" name="totp_code" maxlength="6" class="w-full text-center text-3xl font-black tracking-[0.5em] py-4 border-2 border-gray-200 rounded-xl focus:border-adminAccent focus:ring-4 focus:ring-yellow-500/20 focus:outline-none transition" placeholder="••••••" required autocomplete="off" autofocus>
            </div>

            <button type="submit" class="w-full bg-adminDark text-white font-bold py-3 px-4 rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-900/20 flex justify-center items-center gap-2">
                Doğrula ve Giriş Yap <i class="fa-solid fa-check"></i>
            </button>
        </form>
        
        <div class="mt-6 border-t border-gray-100 pt-6">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition flex items-center justify-center gap-2 mx-auto">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Çıkış Yap / Giriş Ekranına Dön
                </button>
            </form>
        </div>
    </div>
</body>
</html>
