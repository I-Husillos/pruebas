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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->string('region'); // EU_MDR, FDA, LATAM_GENERIC, etc.
            $table->string('default_language', 2);
            $table->json('enabled_languages'); // ["es", "en"]
            $table->boolean('active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->index(['active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
