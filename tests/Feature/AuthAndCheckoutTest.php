<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Tests\TestCase;

class AuthAndCheckoutTest extends TestCase
{
    /** @test */
    public function registration_page_is_accessible()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /** @test */
    public function forgot_password_page_is_accessible()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
    }

    /** @test */
    public function product_can_be_added_to_cart()
    {
        $product = Product::create([
            'title' => 'Test Klavye',
            'slug' => 'test-klavye-' . time(),
            'category_id' => 1,
            'condition_type' => 'new',
            'price' => 1500,
            'stock' => 10
        ]);

        $response = $this->post(route('cart.add', $product->id));
        $response->assertSessionHas('cart');
    }

    /** @test */
    public function checkout_page_redirects_if_cart_is_empty()
    {
        $response = $this->get(route('checkout'));
        $response->assertRedirect(route('cart.index'));
    }
}
