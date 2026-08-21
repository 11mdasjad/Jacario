<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroBannerAndProductImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Executive Performance Overview');
        $response->assertSee('Inventory Alerts');
    }

    public function test_admin_can_view_and_manage_hero_banners(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // 1. View Banner index
        $response = $this->actingAs($admin)->get(route('admin.banners.index'));
        $response->assertStatus(200);
        $response->assertSee('The Polo, Perfected');
        $response->assertSee('Slot #1');

        // 2. Create a new banner
        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Custom Limited Drop Polo',
            'subtitle' => 'Exclusive cashmere-touch collection.',
            'badge_text' => 'Limited Drop',
            'cta_text' => 'Shop Drop',
            'cta_url' => '/shop?collection=new-arrivals',
            'image_url' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=1800',
            'sort_order' => 6,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', ['title' => 'Custom Limited Drop Polo']);

        // 3. Toggle banner active status
        $banner = Banner::where('title', 'Custom Limited Drop Polo')->first();
        $this->actingAs($admin)->post(route('admin.banners.toggle-active', $banner->id));
        $this->assertFalse($banner->fresh()->is_active);
    }

    public function test_admin_can_create_product_with_5_images(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $category = Category::first();

        $imageUrls = [
            'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=900',
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=900',
            'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=900',
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=900',
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'JACARIO Test 5-Image Deluxe Polo',
            'sku' => 'JAC-TEST-5IMG',
            'category_id' => $category->id,
            'base_price' => 2499,
            'sale_price' => 1999,
            'short_description' => 'Test 5-image deluxe polo short elevator description.',
            'description' => 'Detailed craftsmanship description.',
            'fabric' => '100% American Supima Cotton',
            'image_urls' => $imageUrls,
            'primary_image_index' => 0,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'JAC-TEST-5IMG')->first();
        $this->assertNotNull($product);
        $this->assertEquals(5, $product->images()->count());
    }

    public function test_homepage_renders_all_5_banners_and_sections(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // 5 Banners
        $response->assertSee('The Polo, Perfected.');
        $response->assertSee('Mulberry Silk &amp; Supima®', false);
        $response->assertSee('Riviera Earth &amp; Espresso', false);
        $response->assertSee('Loved. Worn. Repeated.');
        $response->assertSee('Collar Engineering That Never Curls.');

        // Sections
        $response->assertSee('New Arrivals');
        $response->assertSee('Best Sellers');
        $response->assertSee('Trending Polos');
        $response->assertSee('Premium Collection');
        $response->assertSee('Everyday Essentials');
        $response->assertSee('All Polo T-Shirts');
    }

    public function test_shop_catalog_renders_3_running_banners(): void
    {
        $response = $this->get(route('shop.index'));
        $response->assertStatus(200);

        // Verify 3 Shop Running Banners
        $response->assertSee('The Polo Collection');
        $response->assertSee('Mulberry Silk &amp; 24-Gauge Knits', false);
        $response->assertSee('Riviera Earth &amp; Espresso Drop', false);
        $response->assertSee('01 / 03');
    }

    public function test_admin_can_create_and_filter_shop_catalog_banners(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // 1. Create a shop position banner
        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'title' => 'Monochrome Knit T-Shirts',
            'subtitle' => 'Minimalist modern silhouette engineered for versatile layering.',
            'badge_text' => '✦ New Minimalist Drop',
            'cta_text' => 'Shop Monochrome',
            'cta_url' => '/shop?q=Monochrome',
            'position' => 'shop',
            'image_url' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=1800',
            'sort_order' => 4,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.banners.index'));

        $this->assertDatabaseHas('banners', [
            'title' => 'Monochrome Knit T-Shirts',
            'position' => 'shop',
        ]);

        // 2. Filter admin banners by position
        $response = $this->actingAs($admin)->get(route('admin.banners.index', ['position' => 'shop']));
        $response->assertStatus(200);
        $response->assertSee('Monochrome Knit T-Shirts');
        $response->assertSee('Shop Slot #4');
    }
}
