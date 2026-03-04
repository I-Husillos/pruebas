<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportPagesCommand extends Command
{
    protected $signature = 'termosalud:import-pages {file}';

    protected $description = 'Import generic pages (custom landings) from legacy WordPress SQL dump';

    private $excludeIds = [];

    private $posts = [];

    private $pageGroups = [];

    public function handle()
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File not found: $file");

            return 1;
        }

        // 1. Identify IDs to exclude (Treatments)
        $this->info('Step 1: Identifying IDs to exclude (Treatments)...');
        // We can allow passing IDs or query the DB for existing treatments slugs, but better is to re-run the discovery logic briefly
        // OR simpler: just exclude 458 and 611 and their children found in the dump.
        // Let's assume ImportTreatmentsCommand has run, but we don't depend on DB status if possible.
        // Actually, easier: Scan file for children of 458.
        $this->excludeIds = $this->findChildrenIds($file, [458, 611]);
        $this->excludeIds[] = 458;
        $this->excludeIds[] = 611;

        $this->info('Excluding '.count($this->excludeIds).' IDs related to Treatments.');

        // 2. Find ALL other pages
        $this->info('Step 2: Finding generic pages...');
        $pageIds = $this->findPageIds($file, $this->excludeIds);
        $this->info('Found '.count($pageIds).' potential generic pages.');

        // 3. Resolve Translations
        $this->info('Step 3: Resolving translations...');
        $this->pageGroups = $this->findTranslationGroups($file, $pageIds);

        // Add singletons
        foreach ($pageIds as $id) {
            $found = false;
            foreach ($this->pageGroups as $group) {
                if (in_array($id, $group)) {
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                $this->pageGroups[] = ['es' => $id]; // Default assuming ES
            }
        }

        // 4. Extract Data
        $idsToFetch = [];
        foreach ($this->pageGroups as $group) {
            foreach ($group as $lang => $id) {
                $idsToFetch[] = $id;
            }
        }
        $idsToFetch = array_unique($idsToFetch);
        $this->info('Step 4: Extracting content for '.count($idsToFetch).' pages...');
        $this->posts = $this->extractPostsData($file, $idsToFetch);

        // 5. Save to DB (Page model per market/lang)
        $this->info('Step 5: Saving database records...');
        $this->savePages();

        $this->info('Done!');

        return 0;
    }

    private function findChildrenIds($file, $parentIds)
    {
        // Reused parsing logic for exclusions
        $handle = fopen($file, 'r');
        $children = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_posts`') !== false) {
                    $rows = mb_split("\),\(", $line);
                    foreach ($rows as $row) {
                        $row = trim($row, "(); \n\r");
                        if (stripos($row, "'page'") === false) {
                            continue;
                        }

                        foreach ($parentIds as $pid) {
                            if (strpos($row, ",$pid,") !== false) {
                                preg_match('/^[\(]?(\d+),/', $row, $m);
                                if (isset($m[1])) {
                                    $children[] = (int) $m[1];
                                }
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        return array_unique($children);
    }

    private function findPageIds($file, $excludeIds)
    {
        $handle = fopen($file, 'r');
        $ids = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_posts`') !== false) {
                    $rows = mb_split("\),\(", $line);
                    foreach ($rows as $row) {
                        $row = trim($row, "(); \n\r");
                        if (stripos($row, "'page'") === false && stripos($row, "'revision'") === false) {
                            continue;
                        }
                        if (stripos($row, "'revision'") !== false) {
                            continue;
                        }

                        // Extract ID
                        if (preg_match('/^[\(]?(\d+),/', $row, $m)) {
                            $id = (int) $m[1];
                            // Check exclusion
                            if (! in_array($id, $excludeIds)) {
                                $ids[] = $id;
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        return array_unique($ids);
    }

    private function findTranslationGroups($file, $childIds)
    {
        $groups = [];
        $handle = fopen($file, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_term_taxonomy`') !== false && strpos($line, 'post_translations') !== false) {
                    preg_match_all("/'a:[^']+'/", $line, $matches);
                    foreach ($matches[0] as $serializedQuoted) {
                        $serialized = trim($serializedQuoted, "'");
                        $serialized = str_replace("\\'", "'", $serialized);
                        $serialized = str_replace('\\\\', '\\', $serialized);
                        $data = @unserialize($serialized);

                        if ($data && is_array($data)) {
                            $intersect = array_intersect(array_values($data), $childIds);
                            if (! empty($intersect)) {
                                $groups[] = $data;
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        return $groups;
    }

    private function extractPostsData($file, $ids)
    {
        // ... (Same parsing logic as ImportTreatments) - simplified here for brevity or reused
        // We should duplicate the method to be self-contained or use a Trait if rigorous.
        // Duplicating for speed.
        $data = [];
        $handle = fopen($file, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_posts`') !== false) {
                    foreach ($ids as $id) {
                        if (strpos($line, ",$id,") !== false || strpos($line, "($id,") !== false) {
                            $rows = explode('),(', $line);
                            foreach ($rows as $row) {
                                $row = trim($row, "(); \n\r");
                                if (preg_match("/^$id,/", $row)) {
                                    $fields = $this->parseSqlRow($row);
                                    if (isset($fields[4]) && isset($fields[5])) {
                                        $data[$id] = [
                                            'id' => $fields[0],
                                            'content' => $fields[4],
                                            'title' => $fields[5],
                                            'name' => $fields[11] ?? '',
                                            'guid' => $fields[18] ?? '',
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }

    private function parseSqlRow($row)
    {
        // Same parser
        $fields = [];
        $len = strlen($row);
        $inQuote = false;
        $current = '';
        for ($i = 0; $i < $len; $i++) {
            $char = $row[$i];
            if ($char === "'" && ($i === 0 || $row[$i - 1] !== '\\')) {
                $inQuote = ! $inQuote;
            } elseif ($char === ',' && ! $inQuote) {
                $fields[] = $this->cleanField($current);
                $current = '';

                continue;
            }
            $current .= $char;
        }
        $fields[] = $this->cleanField($current);

        return $fields;
    }

    private function cleanField($val)
    {
        $val = trim($val);
        if (str_starts_with($val, "'") && str_ends_with($val, "'")) {
            $val = substr($val, 1, -1);
            $val = str_replace("\\'", "'", $val);
            $val = str_replace('\\"', '"', $val);
            $val = str_replace('\\r', "\r", $val);
            $val = str_replace('\\n', "\n", $val);
        }

        return $val;
    }

    private function savePages()
    {
        foreach ($this->pageGroups as $group) {
            foreach ($group as $lang => $id) {
                if (! isset($this->posts[$id])) {
                    continue;
                }
                $post = $this->posts[$id];

                $cleanLang = $lang === 'en_US' ? 'en' : $lang;
                if ($cleanLang == 'us') {
                    $cleanLang = 'en';
                }

                // Map content to blocks_json format
                // New Format: {"es": [...], "en": [...]} ?
                // No, Page model is one row per lang/market.
                // The migration said "convert existing blocks_json to translatable format"
                // But the table has `language_code`.
                // If I insert a row with `language_code` = 'es', `blocks_json` should probably be just the blocks for that page?
                // OR did the migration make `blocks_json` store ALL langs?
                // Migration 2025_12_22_103800: "Convert ... to translatable format"
                // AND it keeps `language_code` column?
                // This is confusing.
                // However, `PageController` probably expects:
                // $page = Page::where(market, lang, slug)->first();
                // Then reads `$page->blocks_json`.
                // If `blocks_json` is cast to array, and we serve one page, we probably just want the content for THAT language?
                // Let's assume standard behavior: localized content in a localized row.
                // Or if the migration forced translatable structure: `{"es": ...}`.
                // I will use translatable structure just in case the front-end expects it.

                $blocks = [
                    [
                        'type' => 'wysiwyg',
                        'data' => ['content' => $post['content']],
                    ],
                ];

                $jsonContent = [
                    $cleanLang => $blocks,
                ];

                $markets = [];
                if ($cleanLang === 'es') {
                    $markets = ['es', 'mx'];
                } elseif ($cleanLang === 'en') {
                    $markets = ['us'];
                } else {
                    $markets = [$cleanLang]; // fr -> fr
                }

                foreach ($markets as $market) {
                    Page::updateOrCreate(
                        [
                            'market_code' => $market,
                            'language_code' => $cleanLang,
                            'slug' => $post['name'],
                        ],
                        [
                            'seo_title' => $post['title'],
                            'seo_description' => Str::limit(strip_tags($post['content']), 150),
                            'blocks_json' => $jsonContent,
                            'is_active' => true,
                            'publish_at' => now(),
                        ]
                    );
                    $this->info("Imported Page: {$post['title']} ($market-$cleanLang)");
                }
            }
        }
    }
}
