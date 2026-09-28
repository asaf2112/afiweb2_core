<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class PcBuilderSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategorileri Kontrol Et / Oluştur
        $catCpu = Category::firstOrCreate(['slug' => 'islemci'], ['name' => 'İşlemci']);
        $catMb  = Category::firstOrCreate(['slug' => 'anakart'], ['name' => 'Anakart']);
        $catRam = Category::firstOrCreate(['slug' => 'ram'], ['name' => 'RAM / Bellek']);
        $catGpu = Category::firstOrCreate(['slug' => 'ekran-karti'], ['name' => 'Ekran Kartı']);
        $catPsu = Category::firstOrCreate(['slug' => 'guc-kaynagi'], ['name' => 'Güç Kaynağı (PSU)']);

        // Sample CPUs
        Product::updateOrCreate(
            ['slug' => 'intel-core-i7-13700k'],
            [
                'title'          => 'Intel Core i7-13700K 3.4GHz 30MB 1700P',
                'category_id'    => $catCpu->id,
                'price'          => 14500.00,
                'stock'          => 10,
                'condition_type' => 'new',
                'socket'         => 'LGA1700',
                'tdp_watt'       => 125,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'amd-ryzen-7-7800x3d'],
            [
                'title'          => 'AMD Ryzen 7 7800X3D 4.2GHz 96MB AM5',
                'category_id'    => $catCpu->id,
                'price'          => 16200.00,
                'stock'          => 8,
                'condition_type' => 'new',
                'socket'         => 'AM5',
                'tdp_watt'       => 120,
            ]
        );

        // Sample Motherboards
        Product::updateOrCreate(
            ['slug' => 'asus-prime-z790-p-wifi'],
            [
                'title'          => 'ASUS PRIME Z790-P WIFI Intel LGA1700 DDR5 Anakart',
                'category_id'    => $catMb->id,
                'price'          => 8200.00,
                'stock'          => 5,
                'condition_type' => 'new',
                'socket'         => 'LGA1700',
                'ram_type'       => 'DDR5',
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'msi-pro-b650-p-wifi'],
            [
                'title'          => 'MSI PRO B650-P WIFI AMD AM5 DDR5 Anakart',
                'category_id'    => $catMb->id,
                'price'          => 7400.00,
                'stock'          => 6,
                'condition_type' => 'new',
                'socket'         => 'AM5',
                'ram_type'       => 'DDR5',
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'gigabyte-b660m-ds3h-ddr4'],
            [
                'title'          => 'GIGABYTE B660M DS3H Intel LGA1700 DDR4 Anakart',
                'category_id'    => $catMb->id,
                'price'          => 4500.00,
                'stock'          => 7,
                'condition_type' => 'new',
                'socket'         => 'LGA1700',
                'ram_type'       => 'DDR4',
            ]
        );

        // Sample RAMs
        Product::updateOrCreate(
            ['slug' => 'kingston-fury-beast-32gb-ddr5-6000mhz'],
            [
                'title'          => 'Kingston Fury Beast 32GB (2x16GB) 6000MHz DDR5 CL36',
                'category_id'    => $catRam->id,
                'price'          => 4800.00,
                'stock'          => 12,
                'condition_type' => 'new',
                'ram_type'       => 'DDR5',
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'corsair-vengeance-lpx-16gb-ddr4-3200mhz'],
            [
                'title'          => 'Corsair Vengeance LPX 16GB (2x8GB) 3200MHz DDR4 CL16',
                'category_id'    => $catRam->id,
                'price'          => 1850.00,
                'stock'          => 15,
                'condition_type' => 'new',
                'ram_type'       => 'DDR4',
            ]
        );

        // Sample GPUs
        Product::updateOrCreate(
            ['slug' => 'msi-geforce-rtx-4070-ti-super-16gb'],
            [
                'title'          => 'MSI GeForce RTX 4070 Ti SUPER 16GB Gaming X Slim',
                'category_id'    => $catGpu->id,
                'price'          => 35500.00,
                'stock'          => 4,
                'condition_type' => 'new',
                'tdp_watt'       => 285,
            ]
        );

        // Sample PSUs
        Product::updateOrCreate(
            ['slug' => 'corsair-rm750e-750w-80-gold'],
            [
                'title'          => 'Corsair RM750e 750W 80+ Gold Modüler Güç Kaynağı',
                'category_id'    => $catPsu->id,
                'price'          => 4600.00,
                'stock'          => 8,
                'condition_type' => 'new',
                'tdp_watt'       => 750,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'cooler-master-500w-80-plus'],
            [
                'title'          => 'Cooler Master Elite 500W 80+ Güç Kaynağı',
                'category_id'    => $catPsu->id,
                'price'          => 1950.00,
                'stock'          => 10,
                'condition_type' => 'new',
                'tdp_watt'       => 500,
            ]
        );
    }
}
