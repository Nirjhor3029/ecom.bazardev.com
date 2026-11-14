<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->unique(); // normalized +8801XXXXXXXXX
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->unsignedSmallInteger('risk_score')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('delivered_orders')->default(0);
            $table->unsignedInteger('returned_orders')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->foreignId('geo_division_id')->nullable()->constrained();
            $table->foreignId('geo_district_id')->nullable()->constrained();
            $table->foreignId('geo_upazila_id')->nullable()->constrained();
            $table->string('area')->nullable();
            $table->string('address_line');
            $table->string('postcode')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customers');
    }
};
