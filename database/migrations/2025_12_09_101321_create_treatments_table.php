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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->json('indications')->nullable(); // Indicaciones médicas
            $table->json('contraindications')->nullable();
            $table->json('procedure_details')->nullable();
            $table->json('images')->nullable();
            $table->json('related_products')->nullable(); // IDs de productos relacionados
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('available_markets')->nullable();
            $table->json('meta_seo')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
