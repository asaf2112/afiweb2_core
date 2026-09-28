<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $username;
    protected $password;
    protected $header;

    public function __construct()
    {
        $this->username = config('services.netgsm.username');
        $this->password = config('services.netgsm.password');
        $this->header = config('services.netgsm.header');
    }

    /**
     * Send an SMS using NetGSM API.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendSms($phone, $message)
    {
        // Temizle: boşlukları kaldır, sadece rakam kalsın
        $phone = preg_replace('/\D/', '', $phone);
        
        // Eğer başında 0 varsa kaldır (NetGSM genelde 5xxxxxxxxx formatı veya ülke kodsuz format ister)
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        try {
            $response = Http::get('https://api.netgsm.com.tr/sms/send/get', [
                'usercode' => $this->username,
                'password' => $this->password,
                'msgheader' => $this->header,
                'gsmno' => $phone,
                'message' => $message,
                'filter' => '0',
                'startdate' => '',
                'stopdate' => '',
            ]);

            $body = $response->body();

            // NetGSM başarılı dönütte "00 " ile başlayan bir string döner (örn: "00 123456789")
            if (str_starts_with($body, '00')) {
                Log::info("NetGSM SMS Başarıyla Gönderildi: {$phone}");
                return true;
            } else {
                Log::error("NetGSM SMS Hata Kodu Döndü: {$body}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("NetGSM SMS Gönderim Hatası: " . $e->getMessage());
            return false;
        }
    }
}
