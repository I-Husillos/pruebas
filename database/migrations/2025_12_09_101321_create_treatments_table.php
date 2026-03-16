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
        Schema::create('treatment_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->index('status');
            $table->index('order');
        });

        Schema::create('treatment_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();

            $table->unique(['treatment_category_id', 'language_id'], 'treat_cat_trans_unique');

        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_category_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'scheduled', 'pending_review'])->default('draft');
            $table->json('images')->nullable();
            $table->json('related_products')->nullable(); 
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('order');
        });

        Schema::create('treatment_localizations', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->foreignId('treatment_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->json('content')->nullable();
            $table->json('indications')->nullable();
            $table->json('contraindications')->nullable();
            $table->json('seo_metadata')->nullable();
            $table->timestamps();

            $table->unique(['treatment_id', 'language_id', 'market_id'], 'treatment_lang_mkt_unique');
            $table->unique(['slug', 'language_id', 'market_id'], 'treatment_localizations_slug_language_market_unique');
            $table->index(['slug', 'language_id', 'market_id'], 'treatment_slug_search_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_category_translations');
        Schema::dropIfExists('treatment_categories');
        Schema::dropIfExists('treatment_localizations');
        Schema::dropIfExists('treatments');
    }
};
