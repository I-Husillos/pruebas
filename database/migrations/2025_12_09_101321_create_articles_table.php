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
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->index('status');
            $table->index('order');
        });

        Schema::create('article_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();

            $table->unique(['article_category_id', 'language_id'], 'article_cat_trans_unique');

        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'scheduled', 'pending_review'])->default('draft');
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

        Schema::create('article_localizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();

            $table->unique(['article_id', 'language_id', 'market_id'], 'article_lang_mkt_unique');
            $table->index(['slug', 'language_id', 'market_id'], 'article_slug_search_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_category_translations');
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('article_localizations');
        Schema::dropIfExists('articles');
    }
};
