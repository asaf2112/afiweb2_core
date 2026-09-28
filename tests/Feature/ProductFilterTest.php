<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;

class ProductFilterTest extends TestCase
{
    /** @test */
    public function products_page_loads_successfully()
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function search_filter_returns_matching_products()
    {
        $product = Product::create([
            'title' => 'NVIDIA RTX 4080 Super Ekran Kartı',
            'slug' => 'nvidia-rtx-4080-super-' . time(),
            'category_id' => 1,
            'condition_type' => 'new',
            'price' => 45000,
            'stock' => 5
        ]);

        $response = $this->get(route('products.index', ['search' => 'RTX 4080']));
        $response->assertStatus(200);
        $response->assertSee('RTX 4080');
    }
}
