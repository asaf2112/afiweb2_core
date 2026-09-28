<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Afi Bilişim</title>
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
    <style>
        @keyframes afiSpin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .afi-btn-spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2.5px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: afiSpin .7s linear infinite;
            vertical-align: middle;
        }
        #login-btn.loading { opacity: 0.8; pointer-events: none; }
    </style>
</head>
<body class="bg-adminDark flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute w-[800px] h-[800px] bg-adminAccent rounded-full mix-blend-multiply filter blur-[150px] opacity-20 top-[-200px] right-[-200px] animate-pulse"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 relative z-10 mx-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-heading font-black tracking-tighter text-adminDark mb-1">
                AFI<span class="text-adminAccent">ADMIN</span>
            </h1>
            <p class="text-gray-500 text-sm">Yönetim Paneli Güvenli Giriş</p>
        </div>

        <form action="#" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">E-Posta Adresi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="email" name="email" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-adminAccent focus:outline-none transition" placeholder="admin@afibilisim.com" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Şifre</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-adminAccent focus:outline-none transition" placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-600 cursor-pointer">
                    <input type="checkbox" class="rounded text-adminAccent focus:ring-adminAccent mr-2 border-gray-300"> Beni Hatırla
                </label>
                <a href="#" class="font-bold text-adminDark hover:text-adminAccent transition">Şifremi Unuttum?</a>
            </div>

            <button type="submit" id="login-btn"
                    class="w-full bg-adminDark text-white font-bold py-3 px-4 rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-900/20 flex justify-center items-center gap-2">
                <span id="login-btn-text">Giriş Yap</span>
                <i id="login-btn-icon" class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function() {
        const btn  = document.getElementById('login-btn');
        const text = document.getElementById('login-btn-text');
        const icon = document.getElementById('login-btn-icon');
        if (!btn) return;
        btn.classList.add('loading');
        if (text) text.textContent = 'Giriş yapılıyor...';
        if (icon) icon.outerHTML = '<span class="afi-btn-spinner"></span>';
    });
    </script>
</body>
</html>
