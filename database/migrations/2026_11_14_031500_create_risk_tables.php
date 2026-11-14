<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->json('config'); // thresholds, action
            $table->timestamps();
        });

        Schema::create('blacklist_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['phone', 'ip', 'device', 'address']);
            $table->string('value');
            $table->string('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklist_entries');
        Schema::dropIfExists('risk_rules');
    }
};
