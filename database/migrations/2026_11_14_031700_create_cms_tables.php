<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('sections')->nullable(); // [{type, settings, sort}]
            $table->string('theme_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cms_menus', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // header, footer, mobile
            $table->json('items'); // [{label, url, sort, children}]
            $table->timestamps();

            $table->unique('key');
        });

        Schema::create('cms_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('home');
            $table->string('type'); // hero, category-grid, product-carousel, banner...
            $table->json('settings');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('placement'); // home_hero, category_top...
            $table->foreignId('image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('link')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('cms_sections');
        Schema::dropIfExists('cms_menus');
        Schema::dropIfExists('cms_pages');
    }
};
