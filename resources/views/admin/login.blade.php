<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Afi Bilişim</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
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
                        afiDark: '#0a0a0a',
                        afiCard: '#121212',
                        afiYellow: '#eab308'
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(18, 18, 18, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .yellow-glow {
            box-shadow: 0 0 40px -10px rgba(234, 179, 8, 0.3);
        }
    </style>
</head>
<body class="bg-afiDark min-h-screen flex items-center justify-center relative overflow-hidden font-sans antialiased">
    
    <!-- Arkaplan Efektleri -->
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-afiYellow rounded-full mix-blend-multiply filter blur-[120px] opacity-10 animate-pulse"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-600 rounded-full mix-blend-multiply filter blur-[150px] opacity-10"></div>
    
    <!-- Giriş Kartı -->
    <div class="glass-panel w-full max-w-md mx-auto p-10 rounded-3xl yellow-glow z-10 mx-4 shadow-2xl">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-afiYellow/10 text-afiYellow text-3xl mb-6">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-3xl font-heading font-black text-white tracking-tight mb-2">Afi Bilişim</h1>
            <p class="text-gray-400 font-medium tracking-wide">Yönetici Paneli Girişi</p>
        </div>

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-500 text-sm p-4 rounded-xl mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- E-posta -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">E-Posta Adresi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@afibilisim.com" 
                           class="w-full bg-black/50 border border-gray-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-afiYellow focus:border-afiYellow block pl-11 p-4 transition-all outline-none placeholder-gray-600">
                </div>
            </div>

            <!-- Şifre -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-semibold text-gray-300">Şifre</label>
                    <a href="#" class="text-xs text-gray-500 hover:text-afiYellow transition-colors">Şifremi Unuttum</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••" 
                           class="w-full bg-black/50 border border-gray-800 text-white text-sm rounded-xl focus:ring-2 focus:ring-afiYellow focus:border-afiYellow block pl-11 p-4 transition-all outline-none placeholder-gray-600">
                </div>
            </div>

            <!-- Beni Hatırla -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" value="1" class="w-4 h-4 text-afiYellow bg-gray-800 border-gray-700 rounded focus:ring-afiYellow focus:ring-2 cursor-pointer">
                <label for="remember" class="ml-2 text-sm font-medium text-gray-400 cursor-pointer select-none">Beni hatırla</label>
            </div>

            <!-- Giriş Butonu -->
            <button type="submit" class="w-full text-black bg-afiYellow hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-yellow-500/50 font-bold rounded-xl text-lg px-5 py-4 text-center transition-all duration-300 shadow-lg shadow-afiYellow/20 hover:shadow-afiYellow/40 transform hover:-translate-y-1 flex justify-center items-center gap-3 mt-4">
                Giriş Yap <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
            
        </form>
        
        <div class="mt-8 text-center border-t border-gray-800 pt-6">
            <p class="text-xs text-gray-600">&copy; {{ date('Y') }} Afi Bilişim. Tüm hakları saklıdır.</p>
        </div>

    </div>

</body>
</html>
