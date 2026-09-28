<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('reference_code')->unique()->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status')->default('Beklemede'); // Beklemede, Hazırlanıyor, Kargolandı, Tamamlandı
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['reference_code', 'total_amount', 'status']);
        });
    }
};
