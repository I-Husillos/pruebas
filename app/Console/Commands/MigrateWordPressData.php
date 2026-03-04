<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateWordPressData extends Command
{
    protected $signature = 'migrate:wordpress {--truncate : Truncate target tables before migration}';

    protected $description = 'Migrate products and articles from the temporary WordPress database';

    private array $marketLangs = ['es', 'en', 'fr', 'pt', 'de', 'us'];

    public function handle()
    {
        $this->info('Starting WordPress migration...');

        // 1. Setup temporary connection
        Config::set('database.connections.wp_migration', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => 'wp_migration',
            'username' => env('DB_USERNAME', 'sail'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);

        if ($this->option('truncate')) {
            $this->warn('Truncating target tables...');
            DB::table('market_languages')->truncate();
            DB::table('products')->truncate();
            DB::table('content_articles')->truncate();
            DB::table('treatments')->truncate();
            DB::table('pages')->truncate();
        }

        // 2. Ensure Languages & Markets exist
        $this->ensureBaseData();

        // 3. Migrate Products (from pages)
        $this->migrateProducts();

        // 4. Migrate Articles (from posts)
        $this->migrateArticles();

        // 5. Migrate Treatments
        $this->migrateTreatments();

        // 6. Migrate Generic Pages
        $this->migratePages();

        $this->info('Migration completed successfully!');

        return 0;
    }

    private function ensureBaseData()
    {
        $this->info('Ensuring base languages and markets exist...');

        $langs = [
            ['code' => 'es', 'name' => 'Español', 'native_name' => 'Español'],
            ['code' => 'en', 'name' => 'Inglés', 'native_name' => 'English'],
            ['code' => 'fr', 'name' => 'Francés', 'native_name' => 'Français'],
            ['code' => 'pt', 'name' => 'Portugués', 'native_name' => 'Português'],
            ['code' => 'de', 'name' => 'Alemán', 'native_name' => 'Deutsch'],
        ];

        foreach ($langs as $lang) {
            DB::table('languages')->updateOrInsert(['code' => $lang['code']], $lang + ['created_at' => now(), 'updated_at' => now()]);
        }

        // Create Global Market if not exists
        DB::table('markets')->updateOrInsert(
            ['code' => 'es'],
            [
                'name' => 'España',
                'region' => 'EU_MDR',
                'default_language' => 'es',
                'enabled_languages' => json_encode(['es', 'en', 'fr', 'pt', 'de']),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $marketId = DB::table('markets')->where('code', 'es')->value('id');
        foreach ($langs as $lang) {
            $langId = DB::table('languages')->where('code', $lang['code'])->value('id');
            DB::table('market_languages')->updateOrInsert(
                ['market_id' => $marketId, 'language_id' => $langId],
                ['is_default' => $lang['code'] === 'es', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    private function migrateProducts()
    {
        $this->info('Migrating Products...');

        $productSlugs = ['criocuum', 'eneka-pro', 'symmed', 'vmat-pro', 'zionic', 'linfopress-evolution-pro', 'symmed-elite-aesthetic'];
        $createdCodes = [];

        try {
            $groups = DB::connection('wp_migration')
                ->table('wp_term_taxonomy as tt')
                ->join('wp_terms as t', 'tt.term_id', '=', 't.term_id')
                ->where('tt.taxonomy', 'post_translations')
                ->select('t.slug', 'tt.description')
                ->get();
        } catch (\Exception $e) {
            $this->error('Could not connect: '.$e->getMessage());

            return;
        }

        foreach ($groups as $group) {
            $mapping = unserialize($group->description);
            if (! $mapping) {
                continue;
            }

            $posts = DB::connection('wp_migration')
                ->table('wp_posts')
                ->whereIn('ID', array_values($mapping))
                ->get();

            if ($posts->isEmpty()) {
                continue;
            }

            $isProduct = false;
            foreach ($posts as $post) {
                if ($post->post_type === 'page' && (in_array($post->post_name, $productSlugs) || Str::is($productSlugs, $post->post_name) || Str::contains($post->post_title, ['Criocuum', 'Eneka', 'Symmed', 'Vmat', 'Zionic', 'Linfopress']))) {
                    $isProduct = true;
                    break;
                }
            }

            if (! $isProduct) {
                continue;
            }

            $localizedNames = [];
            $localizedSlugs = [];
            $localizedDescriptions = [];

            foreach ($mapping as $lang => $postId) {
                $p = $posts->firstWhere('ID', $postId);
                if (! $p) {
                    continue;
                }
                $localizedNames[$lang] = $p->post_title;
                $localizedSlugs[$lang] = $p->post_name;
                $localizedDescriptions[$lang] = $p->post_content;
            }

            if (empty($localizedNames)) {
                continue;
            }

            $firstLang = array_key_first($localizedNames);
            $baseCode = strtoupper(str_replace('-', '_', $localizedSlugs['es'] ?? $localizedSlugs[$firstLang] ?? 'PRD'));
            $code = $baseCode;
            $count = 1;
            while (in_array($code, $createdCodes) || DB::table('products')->where('code', $code)->exists()) {
                $code = $baseCode.'_'.$count++;
            }

            $displayName = $localizedNames['es'] ?? $localizedNames[$firstLang];
            $this->line("Processing Product: {$displayName} -> Code: $code");

            DB::table('products')->insert([
                'code' => $code,
                'name' => json_encode($localizedNames),
                'slug' => json_encode($localizedSlugs),
                'description' => json_encode($localizedDescriptions),
                'published' => true,
                'published_at' => now(),
                'available_markets' => json_encode(['es']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $createdCodes[] = $code;
        }
    }

    private function migrateArticles()
    {
        $this->info('Migrating Articles...');

        $groups = DB::connection('wp_migration')
            ->table('wp_term_taxonomy as tt')
            ->join('wp_terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tt.taxonomy', 'post_translations')
            ->select('t.slug', 'tt.description')
            ->get();

        foreach ($groups as $group) {
            $mapping = unserialize($group->description);
            if (! $mapping) {
                continue;
            }

            $posts = DB::connection('wp_migration')
                ->table('wp_posts')
                ->whereIn('ID', array_values($mapping))
                ->where('post_type', 'post')
                ->where('post_status', 'publish')
                ->get();

            if ($posts->isEmpty()) {
                continue;
            }

            $localizedTitles = [];
            $localizedSlugs = [];
            $localizedExcerpts = [];
            $localizedContents = [];
            $featuredImageId = null;

            foreach ($mapping as $lang => $postId) {
                $p = $posts->firstWhere('ID', $postId);
                if (! $p) {
                    continue;
                }
                $localizedTitles[$lang] = $p->post_title;
                $localizedSlugs[$lang] = $p->post_name;
                $localizedExcerpts[$lang] = $p->post_excerpt;
                $localizedContents[$lang] = $p->post_content;
                if (! $featuredImageId) {
                    $featuredImageId = DB::connection('wp_migration')->table('wp_postmeta')->where('post_id', $postId)->where('meta_key', '_thumbnail_id')->value('meta_value');
                }
            }

            if (empty($localizedTitles)) {
                continue;
            }
            $firstLang = array_key_first($localizedTitles);
            $displayName = $localizedTitles['es'] ?? $localizedTitles[$firstLang];
            $this->line("Processing Article: {$displayName}");

            $imageUrl = null;
            if ($featuredImageId) {
                $imageUrl = DB::connection('wp_migration')->table('wp_posts')->where('ID', $featuredImageId)->value('guid');
            }

            DB::table('content_articles')->insert([
                'type' => 'news',
                'title' => json_encode($localizedTitles),
                'slug' => json_encode($localizedSlugs),
                'excerpt' => json_encode($localizedExcerpts),
                'content' => json_encode($localizedContents),
                'published' => true,
                'published_at' => $posts->first()->post_date,
                'featured_image' => $imageUrl ? json_encode(['url' => $imageUrl]) : null,
                'available_markets' => json_encode(['es']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function migrateTreatments()
    {
        $this->info('Migrating Treatments...');

        $groups = DB::connection('wp_migration')
            ->table('wp_term_taxonomy as tt')
            ->join('wp_terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tt.taxonomy', 'post_translations')
            ->select('t.slug', 'tt.description')
            ->get();

        foreach ($groups as $group) {
            $mapping = unserialize($group->description);
            if (! $mapping || empty($mapping)) {
                continue;
            }

            $posts = DB::connection('wp_migration')
                ->table('wp_posts')
                ->whereIn('ID', array_values($mapping))
                ->whereIn('post_type', ['treatment', 'tratamiento', 'tratamientos'])
                ->where('post_status', 'publish')
                ->get();

            if ($posts->isEmpty()) {
                continue;
            }

            $localizedNames = [];
            $localizedSlugs = [];
            $localizedDescriptions = [];
            $localizedBlocks = [];

            foreach ($mapping as $lang => $postId) {
                $p = $posts->firstWhere('ID', $postId);
                if (! $p) {
                    continue;
                }
                $localizedNames[$lang] = $p->post_title;
                $localizedSlugs[$lang] = $p->post_name;
                $localizedDescriptions[$lang] = $p->post_content;
                $localizedBlocks[$lang] = [
                    ['type' => 'html', 'content' => $p->post_content],
                ];
            }

            if (empty($localizedNames)) {
                continue;
            }

            $firstLang = array_key_first($localizedNames);
            $displayName = $localizedNames['es'] ?? $localizedNames[$firstLang];
            $this->line("Processing Treatment: {$displayName}");

            DB::table('treatments')->insert([
                'name' => json_encode($localizedNames),
                'slug' => json_encode($localizedSlugs),
                'description' => json_encode($localizedDescriptions),
                'blocks_json' => json_encode($localizedBlocks),
                'published' => true,
                'published_at' => $posts->first()->post_date,
                'available_markets' => json_encode(['es']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function migratePages()
    {
        $this->info('Migrating Pages...');

        $productSlugs = ['criocuum', 'eneka-pro', 'symmed', 'vmat-pro', 'zionic', 'linfopress-evolution-pro', 'symmed-elite-aesthetic'];

        $groups = DB::connection('wp_migration')
            ->table('wp_term_taxonomy as tt')
            ->join('wp_terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tt.taxonomy', 'post_translations')
            ->select('t.slug', 'tt.description')
            ->get();

        foreach ($groups as $group) {
            $mapping = unserialize($group->description);
            if (! $mapping || empty($mapping)) {
                continue;
            }

            $posts = DB::connection('wp_migration')
                ->table('wp_posts')
                ->whereIn('ID', array_values($mapping))
                ->where('post_type', 'page')
                ->where('post_status', 'publish')
                ->get();

            if ($posts->isEmpty()) {
                continue;
            }

            // Check if it's a product page (already handled)
            $isProduct = false;
            foreach ($posts as $post) {
                if (in_array($post->post_name, $productSlugs) || Str::is($productSlugs, $post->post_name) || Str::contains($post->post_title, ['Criocuum', 'Eneka', 'Symmed', 'Vmat', 'Zionic', 'Linfopress'])) {
                    $isProduct = true;
                    break;
                }
            }
            if ($isProduct) {
                continue;
            }

            foreach ($mapping as $lang => $postId) {
                $p = $posts->firstWhere('ID', $postId);
                if (! $p) {
                    continue;
                }

                $this->line("Processing Page: {$p->post_title} ($lang)");

                $blockData = [
                    $lang => [
                        [
                            'type' => 'html',
                            'content' => $p->post_content,
                        ],
                    ],
                ];

                DB::table('pages')->updateOrInsert(
                    [
                        'market_code' => 'es',
                        'language_code' => $lang,
                        'slug' => $p->post_name,
                    ],
                    [
                        'seo_title' => $p->post_title,
                        'seo_description' => $p->post_excerpt,
                        'blocks_json' => json_encode($blockData),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
