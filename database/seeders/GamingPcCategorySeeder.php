<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class GamingPcCategorySeeder extends Seeder
{
    public function run(): void
    {
        $desktopCat = Category::where('slug', 'masaustu-bilgisayar')->first();
        
        if (!$desktopCat) {
            $desktopCat = Category::create([
                'name' => 'Masaüstü Bilgisayar',
                'slug' => 'masaustu-bilgisayar',
                'parent_id' => null,
            ]);
        }

        // 1. Subcategory: Oyuncu Kasaları (Gaming PC)
        $gamingSub = Category::firstOrCreate(
            ['slug' => 'oyuncu-kasalari'],
            [
                'name' => 'Oyuncu Kasaları (Gaming PC)',
                'parent_id' => $desktopCat->id,
            ]
        );

        // Ensure parent_id is set if it already existed without parent
        if ($gamingSub->parent_id !== $desktopCat->id) {
            $gamingSub->update(['parent_id' => $desktopCat->id]);
        }

        // 2. Subcategory: Standart Masaüstü Kasalar
        $normalSub = Category::firstOrCreate(
            ['slug' => 'standart-masaustu-kasalar'],
            [
                'name' => 'Standart Masaüstü Kasalar',
                'parent_id' => $desktopCat->id,
            ]
        );

        if ($normalSub->parent_id !== $desktopCat->id) {
            $normalSub->update(['parent_id' => $desktopCat->id]);
        }

        // 3. Seed Sample Gaming PCs
        Product::updateOrCreate(
            ['slug' => 'afi-gaming-beast-rtx4070ti-super-i7-14700k'],
            [
                'title' => 'Afi Gaming Beast — Intel i7 14700K / RTX 4070 Ti Super 16GB / 32GB DDR5 / 1TB NVMe Oyuncu Kasası',
                'category_id' => $gamingSub->id,
                'price' => 54999.00,
                'discount_price' => 49999.00,
                'discount_end_date' => now()->addDays(3),
                'stock' => 7,
                'condition_type' => 'new',
                'badge' => '2K Gaming İdeal',
                'main_image' => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=600&q=80',
                'specs' => [
                    'cpu' => 'Intel Core i7-14700K',
                    'ram' => '32GB DDR5 6000MHz',
                    'gpu' => 'NVIDIA RTX 4070 Ti Super 16GB',
                    'storage' => '1TB NVMe M.2 SSD',
                    'is_gaming' => 'Evet'
                ],
                'description' => 'Afi Bilişim özel toplanmış, maksimum soğutma ve RGB aydınlatmalı canavar oyuncu kasası.'
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'afi-streamer-edition-ryzen7-7800x3d-rtx4080'],
            [
                'title' => 'Afi Streamer Edition — AMD Ryzen 7 7800X3D / RTX 4080 Super / 32GB RGB RAM Oyuncu Bilgisayarı',
                'category_id' => $gamingSub->id,
                'price' => 72500.00,
                'stock' => 4,
                'condition_type' => 'new',
                'badge' => 'Yayıncı Özel',
                'main_image' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=600&q=80',
                'specs' => [
                    'cpu' => 'AMD Ryzen 7 7800X3D',
                    'ram' => '32GB DDR5 RGB',
                    'gpu' => 'NVIDIA RTX 4080 Super 16GB',
                    'storage' => '2TB Gen4 NVMe SSD',
                    'is_gaming' => 'Evet'
                ],
                'description' => 'Kesintisiz 4K oyun deneyimi ve yüksek kaliteli canlı yayınlar için tasarlanmış profesyonel oyuncu kasası.'
            ]
        );

        // 4. Seed Standard Desktop PC
        Product::updateOrCreate(
            ['slug' => 'afi-office-pro-i5-13400-16gb-512gb-pc'],
            [
                'title' => 'Afi Office Pro — Intel Core i5 13400 / 16GB RAM / 512GB NVMe SSD Masaüstü Bilgisayar',
                'category_id' => $normalSub->id,
                'price' => 17499.00,
                'stock' => 12,
                'condition_type' => 'new',
                'badge' => 'F/P Canavarı',
                'main_image' => 'https://images.unsplash.com/photo-1547082299-de196ea013d6?w=600&q=80',
                'specs' => [
                    'cpu' => 'Intel Core i5-13400',
                    'ram' => '16GB DDR4 3200MHz',
                    'gpu' => 'Intel UHD Graphics 770',
                    'storage' => '512GB NVMe M.2 SSD',
                    'is_gaming' => 'Hayır'
                ],
                'description' => 'Ofis, okul ve günlük kullanım için sessiz, hızlı ve enerji tasarruflu standart masaüstü kasa.'
            ]
        );

        // Assign product 10 ("İ7 KASA") to Masaüstü category if exists
        $i7Product = Product::where('slug', 'i7-kasa-10')->first();
        if ($i7Product) {
            $i7Product->update(['category_id' => $normalSub->id]);
        }
    }
}
