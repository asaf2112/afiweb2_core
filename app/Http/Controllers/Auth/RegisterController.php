<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone|nullable|string|email|max:255|unique:users',
            'phone' => ['required_without:email', 'nullable', 'string', 'regex:/^05[0-9]{2}\s?[0-9]{3}\s?[0-9]{2}\s?[0-9]{2}$/', 'unique:users'],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required_without' => 'Lütfen kayıt olmak için e-posta veya telefon numarasından en az birini girin.',
            'phone.required_without' => 'Lütfen kayıt olmak için e-posta veya telefon numarasından en az birini girin.',
            'phone.regex' => 'Telefon numarası geçerli bir Türkiye formatında olmalıdır (Örn: 05XX XXX XX XX)'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($request->phone) {
            // Netgsm / SMS Doğrulama Simülasyonu
            $code = rand(100000, 999999);
            
            DB::table('phone_verification_codes')->insert([
                'user_id' => $user->id,
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $smsService = new \App\Services\SmsService();
            $smsService->sendSms($user->phone, "Afi Bilisim dogrulama kodunuz: {$code}");

            Auth::login($user);

            return redirect()->route('verification.phone')->with('success', 'Kayıt başarılı! Lütfen telefonunuza gelen kodu girin.');
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Kayıt başarılı! Aramıza hoş geldin.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Eğer Google'dan dönen veriyi görmek istersen:
            // dd($googleUser);
            // NOT: Sistemin çalışmaya devam etmesi için burayı yorum satırı yaptım. Gerekirse açabilirsin.
        } catch (\Exception $e) {
            dd('Google Bağlantı Hatası: ' . $e->getMessage());
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Tekrar hoş geldin!');
        }

        // Kullanıcı yoksa yeni müşteri olarak oluştur
        $email = $googleUser->getEmail();
        $username = explode('@', $email)[0];

        $newUser = User::create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make(Str::random(16)),
            'role' => 'customer'
        ]);

        Auth::login($newUser);

        return redirect()->route('home')->with('success', 'Google ile kayıt başarılı! Aramıza hoş geldin.');
    }
}
