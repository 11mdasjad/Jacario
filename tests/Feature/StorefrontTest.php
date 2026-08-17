<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_loads_successfully_with_luxury_hero_and_polo_sections(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('JACARIO');
        $response->assertSee('The Polo, Perfected');
        $response->assertSee('Shop Polo T-Shirts');
    }

    public function test_shop_catalog_page_displays_polo_products_and_style_filters(): void
    {
        $response = $this->get(route('shop.index'));

        $response->assertStatus(200);
        $response->assertSee('The Polo Collection');
        $response->assertSee('Collection Style');
        $response->assertSee("Men's Polo T-Shirt");
    }

    public function test_shop_catalog_filters_by_category(): void
    {
        $category = Category::first();

        $response = $this->get(route('shop.index', ['category' => $category->slug]));

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_product_detail_page_loads_with_variants_and_schema(): void
    {
        $product = Product::with(['category', 'variants', 'images'])->first();

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->fabric);
        $response->assertSee('Sartorial Specifications');
        $response->assertSee('Check Delivery Availability');
        $response->assertSee('application/ld+json', false);
    }

    public function test_static_brand_pages_render_correctly(): void
    {
        $this->get(route('about'))->assertStatus(200)->assertSee('The JACARIO Atelier');
        $this->get(route('contact'))->assertStatus(200)->assertSee('Connect with Our Atelier');
        $this->get(route('faqs'))->assertStatus(200)->assertSee('Frequently Asked Questions');
        $this->get(route('shipping'))->assertStatus(200)->assertSee('Shipping & Delivery');
        $this->get(route('privacy'))->assertStatus(200)->assertSee('Privacy Policy');
        $this->get(route('terms'))->assertStatus(200)->assertSee('Terms & Conditions');
    }

    public function test_sitemap_xml_generates_valid_xml(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $response->assertSee(route('home'));
    }

    public function test_pincode_checker_endpoint(): void
    {
        $response = $this->postJson(route('products.check-pincode'), [
            'pincode' => '400050',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'available' => true,
        ]);
    }
}
