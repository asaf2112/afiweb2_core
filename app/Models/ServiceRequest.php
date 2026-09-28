<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'service_type',
        'device_model',
        'issue_description',
        'description',
    ];

    /**
     * Kullanıcı ilişkisi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Normalized status label helper
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending', 'Yeni' => 'Yeni',
            'in-progress', 'İnceleniyor' => 'İnceleniyor',
            'completed', 'Çözüldü / Tamamlandı', 'Tamamlandı' => 'Çözüldü / Tamamlandı',
            'cancelled', 'İptal' => 'İptal Edildi',
            default => $this->status ?? 'Yeni'
        };
    }
}
