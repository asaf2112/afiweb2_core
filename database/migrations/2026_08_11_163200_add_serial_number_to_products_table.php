<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->unique()->after('slug');
        });

        // Generate automatic unique serial numbers for existing products
        $products = Product::whereNull('serial_number')->get();
        foreach ($products as $product) {
            $product->serial_number = 'AFI-SRN-' . str_pad($product->id, 5, '0', STR_PAD_LEFT);
            $product->save();
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
    }
};
