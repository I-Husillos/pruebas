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
        Schema::create('content_masters', function (Blueprint $table) {
            $table->id();
            // Polymorphic relation to the entity (CorporatePage, ContentArticle, etc.)
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');

            $table->string('language', 2); // 'es', 'en'
            $table->string('market', 2)->nullable(); // NULL = Base global content, 'es' = Specific market override

            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('body')->nullable();
            $table->json('metadata')->nullable(); // Flexible JSON for extras

            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['language', 'market']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_masters');
    }
};
