<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBadgeTest extends TestCase
{
    /** @test */
    public function product_model_calculates_badge_styles_correctly()
    {
        $product = new Product([
            'title' => 'Test Gaming PC',
            'price' => 25000,
            'badge' => 'F/P Canavarı'
        ]);

        $style = $product->badge_style;

        $this->assertNotEmpty($style);
        $this->assertEquals('F/P Canavarı', $style['text']);
        $this->assertEquals('fa-bolt', $style['icon']);
        $this->assertStringContainsString('F/P Canavarı', $style['badge_html']);
    }

    /** @test */
    public function custom_badge_can_be_saved_via_admin_and_rendered()
    {
        $admin = User::factory()->make(['is_admin' => true]);

        $productData = [
            'title' => 'Özel Şampiyon PC',
            'category_id' => 1,
            'condition_type' => 'new',
            'price' => 35000,
            'stock' => 10,
            'badge' => 'E-Sporcu Özel'
        ];

        $product = Product::create([
            'title' => 'Özel Şampiyon PC',
            'slug' => 'ozel-sampiyon-pc',
            'category_id' => 1,
            'condition_type' => 'new',
            'price' => 35000,
            'stock' => 10,
            'badge' => 'E-Sporcu Özel'
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'badge' => 'E-Sporcu Özel'
        ]);

        $this->assertEquals('E-Sporcu Özel', $product->fresh()->badge);
    }
}
