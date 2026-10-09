<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\CustomizableProduct;
use App\Models\CustomizationSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Size;
use App\Models\User;
use Database\Seeders\CustomizationSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\SizeAndColorSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomizationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            SizeAndColorSeeder::class,
            SettingSeeder::class,
            CustomizationSeeder::class,
        ]);
    }

    public function test_customize_hub_page_is_accessible()
    {
        $response = $this->get(route('customize.index'));
        $response->assertStatus(200);
        $response->assertSee('Customize Polo T-Shirt');
        $response->assertSee('Customize T-Shirt');
    }

    public function test_polo_and_tshirt_studio_pages_render_correctly()
    {
        $poloResponse = $this->get(route('customize.studio', 'polo-t-shirt'));
        $poloResponse->assertStatus(200);
        $poloResponse->assertSee('Customize Polo T-Shirt');

        $tshirtResponse = $this->get(route('customize.studio', 't-shirt'));
        $tshirtResponse->assertStatus(200);
        $tshirtResponse->assertSee('Customize T-Shirt');
    }

    public function test_artwork_upload_endpoint_validates_and_stores_files()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('brand_logo.png', 300, 300);

        $response = $this->postJson(route('customize.upload-artwork'), [
            'artwork' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'original_name' => 'brand_logo.png',
        ]);
    }

    public function test_add_customized_garment_to_cart()
    {
        $user = User::where('role', 'customer')->first();
        $product = CustomizableProduct::where('slug', 'polo-t-shirt')->first();
        $color = Color::first();
        $size = Size::first();

        $customizationPayload = [
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'quantity' => 2,
            'customization_data' => [
                'layers' => [
                    [
                        'type' => 'text',
                        'text' => 'TEST JACARIO LOGO',
                        'fontFamily' => 'Cinzel',
                        'color' => '#FFFFFF',
                        'fontSize' => 24,
                        'x' => 50,
                        'y' => 50,
                        'scale' => 1,
                        'rotation' => 0,
                    ]
                ]
            ],
            'preview_image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ];

        $response = $this->actingAs($user)->postJson(route('customize.add-to-cart'), $customizationPayload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $cartResponse = $this->actingAs($user)->getJson(route('cart.summary.api'));
        $cartResponse->assertStatus(200);
        $this->assertEquals(2, $cartResponse->json('item_count'));
    }

    public function test_admin_customization_management_suite_access()
    {
        $admin = User::where('role', 'super_admin')->first();

        // 1. Overview dashboard
        $response = $this->actingAs($admin)->get(route('admin.customizations.index'));
        $response->assertStatus(200);
        $response->assertSee('Customization Overview');

        // 2. Products list
        $prodResponse = $this->actingAs($admin)->get(route('admin.customizations.products'));
        $prodResponse->assertStatus(200);
        $prodResponse->assertSee('Customize Polo T-Shirt');

        // 3. Settings editor
        $product = CustomizableProduct::first();
        $settingsResponse = $this->actingAs($admin)->get(route('admin.customizations.settings', $product->id));
        $settingsResponse->assertStatus(200);
        $settingsResponse->assertSee('Upload Constraints');

        // 4. Update settings
        $updateResponse = $this->actingAs($admin)->post(route('admin.customizations.settings.update', $product->id), [
            'allowed_file_types' => 'png, jpg, jpeg, svg, webp',
            'max_file_size_mb' => 20,
            'max_images' => 5,
            'min_text_len' => 1,
            'max_text_len' => 60,
            'allowed_fonts' => 'Cinzel, Inter, Playfair Display',
            'allow_front' => 1,
            'base_customization_fee' => 0,
            'extra_image_fee' => 99,
            'extra_text_fee' => 49,
            'bulk_min_qty' => [5, 20],
            'bulk_discount' => [10, 25],
        ]);

        $updateResponse->assertSessionHas('success');
    }
}
