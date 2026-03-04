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
        // Fix Markets
        Schema::table('markets', function (Blueprint $table) {
            if (!Schema::hasColumn('markets', 'regulation_type')) {
                $table->string('regulation_type')->nullable()->after('region');
            }
            if (!Schema::hasColumn('markets', 'currency')) {
                $table->string('currency', 10)->nullable()->after('regulation_type');
            }
            // Controller sends 'status', migration had 'active'. Adding 'status' to align.
            if (!Schema::hasColumn('markets', 'status')) {
                $table->boolean('status')->default(true)->after('active');
            }
        });

        // Fix Products
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id'); // constrains not added to avoid failure if table missing
            }
            if (!Schema::hasColumn('products', 'blocks_json')) {
                $table->json('blocks_json')->nullable()->after('technical_specs');
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->json('meta_title')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->json('meta_description')->nullable();
            }
        });

        // Fix Treatments
        Schema::table('treatments', function (Blueprint $table) {
            if (!Schema::hasColumn('treatments', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('treatments', 'blocks_json')) {
                $table->json('blocks_json')->nullable();
            }
            if (!Schema::hasColumn('treatments', 'meta_title')) {
                $table->json('meta_title')->nullable();
            }
            if (!Schema::hasColumn('treatments', 'meta_description')) {
                $table->json('meta_description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropColumn(['regulation_type', 'currency', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'blocks_json', 'meta_title', 'meta_description']);
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'blocks_json', 'meta_title', 'meta_description']);
        });
    }
};
