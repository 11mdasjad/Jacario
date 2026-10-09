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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->nullable()->change();
            $table->boolean('is_customized')->default(false)->after('product_variant_id');
            $table->foreignId('customizable_product_id')->nullable()->after('is_customized')->constrained('customizable_products')->nullOnDelete();
            $table->foreignId('custom_color_id')->nullable()->after('customizable_product_id')->constrained('colors')->nullOnDelete();
            $table->foreignId('custom_size_id')->nullable()->after('custom_color_id')->constrained('sizes')->nullOnDelete();
            $table->json('customization_data')->nullable()->after('custom_size_id');
            $table->string('customization_preview_path')->nullable()->after('customization_data');
            $table->string('artwork_file_path')->nullable()->after('customization_preview_path');
            $table->json('artwork_files')->nullable()->after('artwork_file_path');
            $table->decimal('customization_fee', 10, 2)->default(0.00)->after('artwork_files');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('product_variant_id')->nullable()->change();
            $table->boolean('is_customized')->default(false)->after('product_variant_id');
            $table->foreignId('customizable_product_id')->nullable()->after('is_customized')->constrained('customizable_products')->nullOnDelete();
            $table->json('customization_data')->nullable()->after('customizable_product_id');
            $table->string('customization_preview_path')->nullable()->after('customization_data');
            $table->string('artwork_file_path')->nullable()->after('customization_preview_path');
            $table->json('artwork_files')->nullable()->after('artwork_file_path');
            $table->decimal('customization_fee', 10, 2)->default(0.00)->after('artwork_files');
            $table->string('custom_production_status')->default('pending')->after('customization_fee'); // pending, approved, printing, completed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['customizable_product_id']);
            $table->dropForeign(['custom_color_id']);
            $table->dropForeign(['custom_size_id']);
            $table->dropColumn([
                'is_customized',
                'customizable_product_id',
                'custom_color_id',
                'custom_size_id',
                'customization_data',
                'customization_preview_path',
                'artwork_file_path',
                'artwork_files',
                'customization_fee',
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['customizable_product_id']);
            $table->dropColumn([
                'is_customized',
                'customizable_product_id',
                'customization_data',
                'customization_preview_path',
                'artwork_file_path',
                'artwork_files',
                'customization_fee',
                'custom_production_status',
            ]);
        });
    }
};
