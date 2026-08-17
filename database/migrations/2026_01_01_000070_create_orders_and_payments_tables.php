<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Financials
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('coupon_code')->nullable();
            
            // Statuses
            $table->string('status')->default('pending')->index(); // pending, confirmed, processing, packed, shipped, out_for_delivery, delivered, cancelled, returned, refunded
            $table->string('payment_method')->default('razorpay'); // razorpay, cod
            $table->string('payment_status')->default('pending')->index(); // pending, authorized, captured, failed, refunded
            
            // Customer & Delivery Address details
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            
            // Fulfillment / Tracking
            $table->string('tracking_number')->nullable();
            $table->string('courier_name')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('size_name');
            $table->string('color_name');
            $table->string('color_hex')->nullable();
            $table->string('sku');
            $table->string('image_path')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_id')->nullable()->index(); // Razorpay payment id / order id
            $table->string('razorpay_order_id')->nullable()->index();
            $table->string('razorpay_signature')->nullable();
            $table->string('payment_method')->default('razorpay');
            $table->string('currency')->default('INR');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, captured, failed, refunded
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
