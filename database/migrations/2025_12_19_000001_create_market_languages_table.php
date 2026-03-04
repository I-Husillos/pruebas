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
        Schema::create('market_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            // We reference by code since languages doesn't use auto-id as FK usually,
            // but let's check languages definition. It has 'id'.
            // However, it is cleaner to link to id if available, or code.
            // FEATURES.md suggested referencing code, but matching Laravel conventions with IDs is safer for FKs.
            // Let's stick closer to Laravel conventions: market_id and language_id.
            // Wait, FEATURES.md assumes `language_code` reference. Let's see languages table again.
            // Languages table has `id`.
            // Let's use `language_id` for better referential integrity in Laravel.
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['market_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_languages');
    }
};
