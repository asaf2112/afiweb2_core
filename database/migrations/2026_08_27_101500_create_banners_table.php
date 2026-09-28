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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            
            // Top Badges
            $table->string('top_badge_icon')->nullable();
            $table->string('top_badge_text')->nullable();
            $table->string('top_badge_color')->default('amber'); // amber, cyan, blue, red, emerald
            
            // Feature Tags
            $table->string('tag1_icon')->nullable();
            $table->string('tag1_text')->nullable();
            $table->string('tag2_icon')->nullable();
            $table->string('tag2_text')->nullable();
            $table->string('tag3_icon')->nullable();
            $table->string('tag3_text')->nullable();
            
            // Primary Button
            $table->string('button_text')->default('Keşfet');
            $table->string('button_url')->nullable();
            
            // Decorative Right Product Card
            $table->string('card_header')->nullable();
            $table->string('card_badge')->nullable();
            $table->string('card_title')->nullable();
            $table->string('card_spec1')->nullable();
            $table->string('card_spec2')->nullable();
            $table->string('card_spec3')->nullable();
            $table->string('card_old_price')->nullable();
            $table->string('card_price')->nullable();
            
            // Product Card Direct Action Button (Feature 1 requested by USER)
            $table->string('card_button_text')->default('Ürüne Git');
            $table->string('card_button_url')->nullable();
            
            // Custom Image & Theme Options
            $table->string('image_path')->nullable();
            $table->string('bg_gradient')->default('from-[#1a1103] via-[#140d02] to-[#090b10]');
            $table->string('glow_color')->default('amber');
            $table->string('watermark_text')->nullable();
            
            // Controls
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
