<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateStockSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('stock', '>', 5)->take(3)->get();
        foreach ($products as $index => $p) {
            $p->update(['stock' => $index + 2]);
        }
    }
}
