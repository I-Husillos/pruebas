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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // SKU interno
            $table->json('name'); // {"es": "Zionic Pro Max", "en": "Zionic Pro Max"}
            $table->json('slug'); // {"es": "zionic-pro-max", "en": "zionic-pro-max"}
            $table->json('short_description')->nullable();
            $table->json('description')->nullable();
            $table->json('technical_specs')->nullable();
            $table->json('images')->nullable(); // [{"url": "...", "alt": "..."}]
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('available_markets')->nullable(); // ["es", "mx", "us"]
            $table->json('meta_seo')->nullable(); // {"title": {"es": "..."}, "description": {...}}
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published', 'published_at']);
            $table->index('category');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
