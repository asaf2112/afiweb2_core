<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PhoneVerificationController extends Controller
{
    public function showVerifyForm()
    {
        $user = Auth::user();
        if ($user->phone_verified_at) {
            return redirect()->route('home');
        }
        
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        $verification = DB::table('phone_verification_codes')
            ->where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'Girdiğiniz doğrulama kodu hatalı veya süresi dolmuş.']);
        }

        $user->phone_verified_at = now();
        $user->save();

        DB::table('phone_verification_codes')->where('user_id', $user->id)->delete();

        return redirect()->route('home')->with('success', 'Telefon numaranız başarıyla doğrulandı! Hoş geldiniz.');
    }

    public function resend()
    {
        $user = Auth::user();

        if ($user->phone_verified_at) {
            return redirect()->route('home');
        }

        $lastCode = DB::table('phone_verification_codes')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        if ($lastCode && \Carbon\Carbon::parse($lastCode->created_at)->addMinute()->isFuture()) {
            return back()->withErrors(['code' => 'Lütfen yeni kod istemeden önce 1 dakika bekleyin.']);
        }

        $code = rand(100000, 999999);

        DB::table('phone_verification_codes')->where('user_id', $user->id)->delete();

        DB::table('phone_verification_codes')->insert([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $smsService = new \App\Services\SmsService();
        $smsService->sendSms($user->phone, "Afi Bilisim dogrulama kodunuz: {$code}");

        return back()->with('success', 'Yeni doğrulama kodu telefonunuza gönderildi!');
    }
}
