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
        Schema::table('change_controls', function (Blueprint $table) {
            $table->nullableMorphs('changeable'); // changeable_type, changeable_id
            $table->json('payload')->nullable();
            $table->string('type')->default('update'); // create, update, delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_controls', function (Blueprint $table) {
            $table->dropMorphs('changeable');
            $table->dropColumn(['payload', 'type']);
        });
    }
};
