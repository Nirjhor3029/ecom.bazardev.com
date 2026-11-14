<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('match'); // district/upazila ids
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('shipping_zones')->cascadeOnDelete();
            $table->string('method')->default('standard');
            $table->unsignedInteger('min_weight')->nullable();
            $table->unsignedInteger('max_weight')->nullable();
            $table->unsignedBigInteger('min_subtotal')->nullable();
            $table->unsignedBigInteger('max_subtotal')->nullable();
            $table->unsignedBigInteger('fee')->default(0);
            $table->unsignedTinyInteger('est_days_min')->nullable();
            $table->unsignedTinyInteger('est_days_max')->nullable();
            $table->unsignedBigInteger('free_over')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zones');
    }
};
