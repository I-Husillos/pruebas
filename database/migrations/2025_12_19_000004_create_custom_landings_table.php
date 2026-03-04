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
        Schema::create('custom_landings', function (Blueprint $table) {
            $table->id();
            $table->string('market_code', 2);
            $table->string('language_code', 2);
            $table->string('slug'); // 'black-friday-2025'

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->json('blocks_json')->nullable(); // The orderly content blocks

            $table->boolean('is_active')->default(false);
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('unpublish_at')->nullable();

            $table->timestamps();

            // Unique slug per market/lang combo
            $table->unique(['market_code', 'language_code', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_landings');
    }
};
