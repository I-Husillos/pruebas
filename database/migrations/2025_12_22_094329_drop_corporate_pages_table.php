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
        Schema::dropIfExists('corporate_pages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('corporate_pages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('is_shared_across_markets')->default(true);
            $table->json('restricted_to_markets')->nullable();
            $table->timestamps();
        });
    }
};
