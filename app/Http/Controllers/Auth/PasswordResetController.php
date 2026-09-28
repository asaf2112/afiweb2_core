<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Forgot Password - Form Gösterme
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Forgot Password - Mail Gönderme
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Eğer bu e-posta sistemimizde kayıtlıysa, şifre sıfırlama bağlantısını gönderdik.');
        }

        // Güvenlik gereği her zaman aynı mesajı vererek mail enumerasyonunu engelliyoruz.
        return back()->with('status', 'Eğer bu e-posta sistemimizde kayıtlıysa, şifre sıfırlama bağlantısını gönderdik.');
    }

    // Reset Password - Form Gösterme
    public function edit(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request, 'token' => $token]);
    }

    // Reset Password - Şifre Güncelleme
    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.')
                    : back()->withErrors(['email' => __($status)]);
    }
}
