<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reference_code',
        'total_amount',
        'status',
        'shipping_company',
        'tracking_number',
        'shipped_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    /**
     * Kargo firmasına göre doğrudan takip linki oluşturur.
     */
    public function getTrackingUrlAttribute(): ?string
    {
        if (empty($this->tracking_number)) {
            return null;
        }

        $code = trim($this->tracking_number);
        $company = strtolower($this->shipping_company ?? '');

        if (str_contains($company, 'yurtiçi') || str_contains($company, 'yurtici')) {
            return "https://www.yurticikargo.com/tr/online-servisler/kargo-takip?code={$code}";
        } elseif (str_contains($company, 'aras')) {
            return "https://www.araskargo.com.tr/kargo-takip/{$code}";
        } elseif (str_contains($company, 'mng')) {
            return "https://www.mngkargo.com.tr/kargotakip?trackNumber={$code}";
        } elseif (str_contains($company, 'sürat') || str_contains($company, 'surat')) {
            return "https://www.suratkargo.com.tr/KargoTakip/?kargotakipno={$code}";
        } elseif (str_contains($company, 'ptt')) {
            return "https://gonderitakip.ptt.gov.tr/Track/Verify?barcode={$code}";
        } elseif (str_contains($company, 'trendyol')) {
            return "https://kargo.trendyol.com/kargo-takip?trackingCode={$code}";
        } elseif (str_contains($company, 'hepsijet')) {
            return "https://www.hepsijet.com/takip/{$code}";
        } elseif (str_contains($company, 'kolay gelsin')) {
            return "https://kolaygelsin.com/kargo-takip/{$code}";
        }

        return "https://www.google.com/search?q=" . urlencode(($this->shipping_company ?? 'Kargo') . " kargo takip " . $code);
    }

    /**
     * Get the user that placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
