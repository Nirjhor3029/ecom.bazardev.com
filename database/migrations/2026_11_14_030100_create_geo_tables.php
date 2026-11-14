<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bn_name')->nullable();
        });

        Schema::create('geo_districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('geo_division_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('bn_name')->nullable();
        });

        Schema::create('geo_upazilas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('geo_district_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('bn_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_upazilas');
        Schema::dropIfExists('geo_districts');
        Schema::dropIfExists('geo_divisions');
    }
};
