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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['draft', 'published', 'scheduled', 'pending_review'])->default('draft');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

        Schema::create('page_localizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();

            $table->unique(['page_id', 'language_id', 'market_id'], 'page_lang_mkt_unique');
            $table->index(['slug', 'language_id', 'market_id'], 'page_slug_search_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_category_translations');
        Schema::dropIfExists('page_categories');
        Schema::dropIfExists('page_localizations');
        Schema::dropIfExists('pages');
    }
};
