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
            $table->json('meta_title')->nullable()->after('slug');
            $table->json('meta_description')->nullable()->after('meta_title');
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->json('meta_title')->nullable()->after('slug');
            $table->json('meta_description')->nullable()->after('meta_title');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('seo_title');
            $table->text('meta_description')->nullable()->after('seo_description');
        });

        Schema::table('content_articles', function (Blueprint $table) {
            $table->json('meta_title')->nullable()->after('slug');
            $table->json('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('content_articles', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
