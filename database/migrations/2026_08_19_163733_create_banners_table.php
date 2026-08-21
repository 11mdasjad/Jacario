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
            $table->string('badge_text')->nullable();
            $table->string('cta_text')->default('Shop Now');
            $table->string('cta_url')->default('/shop');
            $table->string('image_path');
            $table->string('mobile_image_path')->nullable();
            $table->integer('sort_order')->default(1)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->string('text_alignment')->default('left');
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
