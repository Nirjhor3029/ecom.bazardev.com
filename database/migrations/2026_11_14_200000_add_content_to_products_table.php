<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rich storytelling content for the product page (highlights, harvest metrics,
        // sensory profile, lab data, origin story, FAQ). Kept separate from `attributes`
        // so the admin attribute validation never touches it.
        Schema::table('products', function (Blueprint $table) {
            $table->json('content')->nullable()->after('attributes');
        });

        // Storefront review cards show the reviewer's area and a "verified" badge.
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('location')->nullable()->after('body');
            $table->boolean('is_verified')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['location', 'is_verified']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
};
