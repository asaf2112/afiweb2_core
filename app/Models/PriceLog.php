<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceLog extends Model
{
    protected $fillable = [
        'product_id',
        'b2b_price_usd',
        'exchange_rate',
        'old_price_try',
        'new_price_try'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
