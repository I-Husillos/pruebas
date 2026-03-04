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
        Schema::table('products', function (Blueprint $table) {
            $table->json('blocks_json')->nullable()->after('technical_specs');
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->json('blocks_json')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('blocks_json');
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn('blocks_json');
        });
    }
};
