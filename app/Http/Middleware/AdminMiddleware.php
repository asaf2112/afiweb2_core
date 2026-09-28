<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Oturum Kontrolü: Misafir kullanıcıları doğrudan admin girişine yönlendir
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // 2. Rol Kontrolü: Standart / Yetkisiz kullanıcıları kesin olarak engelle
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Bu alana erişim yetkiniz yok. (Yetkisiz Erişim)');
        }

        // 3. 2FA İki Aşamalı Güvenlik Kontrolü
        if ($user->two_factor_enabled && !empty($user->two_factor_secret)) {
            $is2faVerified = $request->session()->get('2fa_verified', false);
            
            // Eğer 2FA doğrulanmamışsa ve çağrılan rota 2FA doğrulama/çıkış değilse 2FA ekranına zorunlu yönlendir
            if (!$is2faVerified) {
                if (!$request->routeIs('admin.2fa.verify', 'admin.2fa.verify.post', 'admin.logout')) {
                    return redirect()->route('admin.2fa.verify')
                        ->withErrors(['totp_code' => 'Yönetim paneline erişmek için lütfen 2FA doğrulama kodunuzu giriniz.']);
                }
            }
        }

        return $next($request);
    }
}
