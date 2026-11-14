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
            $table->string('order_no')->unique(); // e.g. ORD-2611-000123
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->json('guest_snapshot')->nullable(); // name/phone/email for guest orders

            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'packed', 'shipped',
                'delivered', 'cancelled', 'returned', 'failed', 'on_hold',
            ])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded', 'partially_refunded'])->default('unpaid');
            $table->enum('fulfillment_status', ['unfulfilled', 'partial', 'fulfilled'])->default('unfulfilled');

            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->unsignedBigInteger('tax_total')->default(0);
            $table->unsignedBigInteger('grand_total')->default(0);
            $table->unsignedBigInteger('paid_total')->default(0);
            $table->unsignedBigInteger('due_total')->default(0);
            $table->string('currency', 3)->default('BDT');

            $table->string('payment_method')->nullable();
            $table->string('coupon_code')->nullable();
            $table->json('shipping_address'); // snapshot
            $table->json('billing_address')->nullable();
            $table->text('note_customer')->nullable();
            $table->text('note_internal')->nullable();
            $table->enum('source', ['web', 'admin', 'api', 'app'])->default('web');
            $table->json('risk_flags')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->uuid('idempotency_key')->unique();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->json('name_snapshot');
            $table->string('sku');
            $table->json('options')->nullable();
            $table->unsignedBigInteger('unit_price');
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('tax')->default(0);
            $table->unsignedBigInteger('line_total');
            $table->unsignedInteger('weight_grams')->nullable();
            $table->foreignId('batch_id')->nullable()->constrained('inventory_batches')->nullOnDelete();
            // GRC-2 (Phase 2 weight billing) hooks:
            $table->decimal('est_qty', 10, 3)->nullable();
            $table->decimal('actual_qty', 10, 3)->nullable();
            $table->unsignedBigInteger('price_per_unit')->nullable();
            $table->timestamps();
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from')->nullable();
            $table->string('to');
            $table->string('actor_type')->nullable(); // staff|system|customer
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
