<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['return', 'exchange', 'damaged', 'missing']);
            $table->string('reason');
            $table->enum('status', ['requested', 'approved', 'rejected', 'received', 'refunded', 'exchanged', 'closed'])->default('requested');
            $table->json('items'); // [{order_item_id, qty}]
            $table->json('media')->nullable();
            $table->unsignedBigInteger('refund_amount')->nullable();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};
