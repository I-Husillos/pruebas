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
        Schema::create('corporate_pages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'about-us', 'contact'
            $table->boolean('is_shared_across_markets')->default(true);
            $table->json('restricted_to_markets')->nullable(); // ["es", "mx"] or NULL for all
            $table->timestamps();
        });

        // Content for corporate pages will be stored in a unified way or
        // we need a separate table for generic content content?
        // FEATURES.md implies a "ContentMaster" model, but we decided to stick to `content_articles` for dynamic content.
        // For corporate static pages that need translation, we can use a similar approach or a simple JSON field if they are small.
        // However, `FEATURES.md` describes a sophisticated override system.
        // To implement that complexity, we likely need a `content_masters` table that can be polymorphic or specific.
        // Let's create `content_masters` to handle the multilingual content + overrides logic described.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporate_pages');
    }
};
