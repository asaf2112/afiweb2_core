<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            '⚡ F/P Canavarı',
            '🎧 Yayıncı Özel',
            '🎮 2K Gaming İdeal',
            '🔥 Fırsat Ürünü',
            '⭐ Editörün Seçimi'
        ];

        $products = Product::take(5)->get();
        foreach ($products as $index => $product) {
            $product->update([
                'badge' => $badges[$index % count($badges)]
            ]);
        }
    }
}
