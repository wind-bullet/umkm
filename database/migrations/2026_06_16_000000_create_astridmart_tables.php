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
        // 1. admin_emails
        Schema::create('admin_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // 2. categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. voucher_items
        Schema::create('voucher_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('voucher_type'); // e.g. "discount_percentage", "discount_fixed"
            $table->string('voucher_label'); // e.g. "10% OFF"
            $table->timestamps();
        });

        // 5. carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 6. cart_items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price_snapshot', 12, 2);
            $table->timestamps();
        });

        // 7. orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_code')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('payment_method');
            $table->string('delivery_method');
            $table->text('shipping_address')->nullable();
            $table->string('order_status')->default('menunggu_pembayaran');
            $table->timestamps();
        });

        // 8. order_items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price_snapshot', 12, 2);
            $table->timestamps();
        });

        // 9. payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('payment_status')->default('pending');
            $table->string('payment_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 10. messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message_text');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 11. notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 12. reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('review_text')->nullable();
            $table->timestamps();
        });

        // 13. shipping_options
        Schema::create('shipping_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fee_type'); // e.g. "fixed"
            $table->decimal('fee_value', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 14. payment_methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('shipping_options');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('voucher_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('admin_emails');
    }
};
