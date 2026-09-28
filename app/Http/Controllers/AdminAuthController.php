<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Eğer kullanıcı zaten giriş yapmışsa ve admin ise direkt dashboard'a yönlendir
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home')->with('error', 'Bu alana erişim yetkiniz bulunmamaktadır.');
        }
        return view('admin.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();

                // 2FA İki Aşamalı Doğrulama aktifse doğrulamaya yönlendir
                if ($user->two_factor_enabled && !empty($user->two_factor_secret)) {
                    $request->session()->put('2fa_verified', false);
                    return redirect()->route('admin.2fa.verify');
                }

                $request->session()->put('2fa_verified', true);
                return redirect()->intended('/admin');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Bu alana erişim yetkiniz yok.'])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Sağlanan kimlik bilgileri kayıtlarımızla eşleşmiyor.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
