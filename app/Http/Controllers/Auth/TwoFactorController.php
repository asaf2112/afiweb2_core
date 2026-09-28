<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Admin login olduktan sonra 2FA kodunu girmesi için formu gösterir.
     */
    public function showVerifyForm()
    {
        return view('auth.2fa_verify');
    }

    /**
     * Google Authenticator'dan gelen 6 haneli kodu doğrular.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'totp_code' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();
        
        // Eğer kullanıcıda 2FA aktif değilse veya secret yoksa doğrulamayı pas geç (opsiyonel güvenlik)
        if (!$user->two_factor_enabled || empty($user->two_factor_secret)) {
            $request->session()->put('2fa_verified', true);
            return redirect()->route('admin.dashboard');
        }

        // Secret Key ve girilen kodu karşılaştırarak doğrula
        $valid = Google2FA::verifyKey($user->two_factor_secret, $request->totp_code);

        if ($valid) {
            // Doğrulama başarılıysa session'a yetkiyi yaz
            $request->session()->put('2fa_verified', true);
            return redirect()->route('admin.dashboard')->with('success', 'Güvenli giriş başarılı.');
        }

        // Başarısız olursa hatayla geri döndür
        return back()->withErrors(['totp_code' => 'Girdiğiniz doğrulama kodu geçersiz veya süresi dolmuş. Lütfen uygulamadaki güncel kodu girin.']);
    }

    /**
     * Admin panelinden kullanıcının 2FA'yı ilk kez kurması (QR Kod üretimi).
     */
    public function setup()
    {
        $user = Auth::user();
        // Kullanıcı için yeni bir secret anahtarı (Seed) üret
        $secret = Google2FA::generateSecretKey();
        
        // Bu secret'ı veritabanına kaydet (henüz enabled = false kalacak)
        $user->two_factor_secret = $secret;
        $user->save();

        // Google Authenticator vb. uygulamaların okuması için QR Kod URL'si üret
        $qrCodeUrl = Google2FA::getQRCodeInline(
            'Afi Bilisim Admin',
            $user->email,
            $secret
        );

        // Kullanıcı QR kodu okutup ekrandan doğrulama yapmak zorundadır
        return view('admin.2fa_setup', compact('qrCodeUrl', 'secret'));
    }

    /**
     * Kurulum sırasında QR kodu okuttuktan sonra ilk doğrulamanın yapılması ve Sistemin Açılması.
     */
    public function confirmSetup(Request $request)
    {
        $request->validate(['totp_code' => 'required|numeric|digits:6']);
        
        $user = Auth::user();
        
        $valid = Google2FA::verifyKey($user->two_factor_secret, $request->totp_code);

        if ($valid) {
            // Kod doğruysa 2FA'yı resmen aktif et
            $user->two_factor_enabled = true;
            $user->save();
            
            return redirect()->back()->with('success', 'İki Aşamalı Doğrulama başarıyla aktifleştirildi. Artık girişlerde kod sorulacaktır.');
        }

        return back()->withErrors(['totp_code' => 'Kod hatalı. Lütfen QR kodu uygulamanızdan doğru tarattığınızdan emin olun.']);
    }
}
