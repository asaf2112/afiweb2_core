<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'top_badge_icon',
        'top_badge_text',
        'top_badge_color',
        'tag1_icon',
        'tag1_text',
        'tag2_icon',
        'tag2_text',
        'tag3_icon',
        'tag3_text',
        'button_text',
        'button_url',
        'card_header',
        'card_badge',
        'card_title',
        'card_spec1',
        'card_spec2',
        'card_spec3',
        'card_old_price',
        'card_price',
        'card_button_text',
        'card_button_url',
        'image_path',
        'bg_gradient',
        'glow_color',
        'watermark_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Active banners scope
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordered banners scope
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}
