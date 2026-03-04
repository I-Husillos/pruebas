<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            // Add meta_keywords for SEO
            $table->text('meta_keywords')->nullable()->after('seo_description');
        });

        // Convert existing blocks_json to translatable format
        // Old format: [{"type": "row", ...}]
        // New format: {"es": [{"type": "row", ...}], "en": [{"type": "row", ...}]}

        $pages = DB::table('pages')->get();

        foreach ($pages as $page) {
            if ($page->blocks_json) {
                $blocks = json_decode($page->blocks_json, true);

                // If it's already an array of blocks (old format), convert to translatable
                if (is_array($blocks) && ! isset($blocks['es']) && ! isset($blocks['en'])) {
                    $translatableBlocks = [
                        $page->language_code => $blocks,
                    ];

                    DB::table('pages')
                        ->where('id', $page->id)
                        ->update(['blocks_json' => json_encode($translatableBlocks)]);
                }
            }
        }
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
        });

        // Revert blocks_json to old format (take only the current language)
        $pages = DB::table('pages')->get();

        foreach ($pages as $page) {
            if ($page->blocks_json) {
                $blocks = json_decode($page->blocks_json, true);

                if (is_array($blocks) && isset($blocks[$page->language_code])) {
                    DB::table('pages')
                        ->where('id', $page->id)
                        ->update(['blocks_json' => json_encode($blocks[$page->language_code])]);
                }
            }
        }
    }
};
