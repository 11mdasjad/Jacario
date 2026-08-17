<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // S, M, L, XL, XXL
            $table->string('code')->unique();
            $table->string('chest')->nullable();
            $table->string('length')->nullable();
            $table->string('shoulder')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Obsidian Black, Pure White, Royal Navy, etc.
            $table->string('slug')->unique();
            $table->string('hex_code'); // #0B0D10
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
        Schema::dropIfExists('sizes');
    }
};
