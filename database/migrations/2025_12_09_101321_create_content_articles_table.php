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
        Schema::create('content_articles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['blog', 'news', 'press'])->default('blog');
            $table->json('title'); // {"es": "Título", "en": "Title"}
            $table->json('slug');
            $table->json('excerpt')->nullable();
            $table->json('content')->nullable();
            $table->string('author')->nullable();
            $table->json('featured_image')->nullable(); // {"url": "...", "alt": {...}}
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('available_markets')->nullable();
            $table->json('meta_seo')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_articles');
    }
};
