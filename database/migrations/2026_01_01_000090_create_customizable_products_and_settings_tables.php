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
        Schema::create('customizable_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['polo_tshirt', 'tshirt'])->default('polo_tshirt');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(1499.00);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->string('featured_image')->nullable();
            $table->json('mockup_views')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('customizable_product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customizable_product_id')->constrained('customizable_products')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete();
            $table->foreignId('color_id')->constrained('colors')->cascadeOnDelete();
            $table->integer('stock_quantity')->default(100);
            $table->decimal('price_override', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['customizable_product_id', 'size_id', 'color_id'], 'cust_prod_variant_unique');
        });

        Schema::create('customization_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customizable_product_id')->unique()->constrained('customizable_products')->cascadeOnDelete();
            $table->boolean('allow_front')->default(true);
            $table->boolean('allow_back')->default(false);
            $table->json('allowed_file_types')->nullable();
            $table->integer('max_file_size_mb')->default(15);
            $table->json('allowed_fonts')->nullable();
            $table->integer('min_text_len')->default(1);
            $table->integer('max_text_len')->default(40);
            $table->integer('max_images')->default(4);
            $table->json('print_area')->nullable();
            $table->decimal('base_customization_fee', 10, 2)->default(0.00);
            $table->decimal('extra_image_fee', 10, 2)->default(100.00);
            $table->decimal('extra_text_fee', 10, 2)->default(50.00);
            $table->json('bulk_pricing')->nullable();
            $table->timestamps();
        });

        Schema::create('saved_custom_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customizable_product_id')->constrained('customizable_products')->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->string('title')->default('My Custom Design');
            $table->longText('design_data');
            $table->string('preview_path')->nullable();
            $table->json('artwork_paths')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_custom_designs');
        Schema::dropIfExists('customization_settings');
        Schema::dropIfExists('customizable_product_variants');
        Schema::dropIfExists('customizable_products');
    }
};
