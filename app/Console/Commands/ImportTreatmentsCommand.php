<?php

namespace App\Console\Commands;

use App\Models\Treatment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTreatmentsCommand extends Command
{
    protected $signature = 'termosalud:import-treatments {file} {--parent=611}';

    protected $description = 'Import treatments from a legacy WordPress SQL dump';

    private $posts = [];

    private $treatmentGroups = [];

    private $parentIds = [];

    public function handle()
    {
        $file = $this->argument('file');
        $parentId = $this->option('parent');

        if (! file_exists($file)) {
            $this->error("File not found: $file");

            return 1;
        }

        $this->info("Starting import from $file...");

        // Step 1: Find translations of the Parent ID to get ALL container pages
        $this->info("Step 1: finding parent translations for ID $parentId...");
        $this->parentIds = $this->findTranslationsForId($file, $parentId);
        $this->parentIds[] = (int) $parentId;
        $this->parentIds = array_unique($this->parentIds);
        $this->info('Parent IDs found: '.implode(', ', $this->parentIds));

        // Step 2: Find all post IDs that are children of these parents
        $this->info('Step 2: Scanning for children pages...');
        $childIds = $this->findChildrenIds($file, $this->parentIds);
        $this->info('Found '.count($childIds).' potential treatment pages.');

        // Step 3: Find translation groups for these children
        $this->info('Step 3: resolving translations for treatments...');
        $this->treatmentGroups = $this->findTranslationGroups($file, $childIds);

        // Also add singletons (pages with no translations found in the group map)
        foreach ($childIds as $id) {
            $found = false;
            foreach ($this->treatmentGroups as $group) {
                if (in_array($id, $group)) {
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                $this->treatmentGroups[] = ['es' => $id]; // Default to ES if unknown? Or logic to detect lang?
                // Actually, without term_relationships link to 'language' taxonomy, we refrain from guessing too much.
                // But Polylang usually ensures 'post_translations' entry exists.
                // Let's rely on what we found.
            }
        }

        // Flatten all relevant IDs to fetch data
        $idsToFetch = [];
        foreach ($this->treatmentGroups as $group) {
            foreach ($group as $lang => $id) {
                $idsToFetch[] = $id;
            }
        }
        $idsToFetch = array_unique($idsToFetch);

        // Step 4: Extract data for these IDs
        $this->info('Step 4: Extracting content for '.count($idsToFetch).' pages...');
        $this->posts = $this->extractPostsData($file, $idsToFetch);

        // Step 5: Save to DB
        $this->info('Step 5: Saving database records...');
        $this->saveTreatments();

        $this->info('Done!');

        return 0;
    }

    private function findTranslationsForId($file, $targetId)
    {
        // Scan wp_term_taxonomy for post_translations containing this ID
        $handle = fopen($file, 'r');
        $relatedIds = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_term_taxonomy`') !== false) {
                    // Primitive parser for serialized arrays in SQL dump line
                    // Look for "a:X:{...}" that contains our ID
                    // Pattern: i:TARGET_ID;
                    if (strpos($line, "i:$targetId;") !== false && strpos($line, 'post_translations') !== false) {
                        preg_match("/'a:[^']+'/", $line, $matches);
                        if (! empty($matches[0])) {
                            $serialized = trim($matches[0], "'");
                            // Fix for escaped quotes in SQL dump: \' -> '
                            $serialized = str_replace("\\'", "'", $serialized);
                            $serialized = str_replace('\\\\', '\\', $serialized); // Fix backslashes

                            try {
                                $data = @unserialize($serialized);
                                if ($data && is_array($data) && in_array($targetId, $data)) {
                                    $relatedIds = array_merge($relatedIds, array_values($data));
                                }
                            } catch (\Exception $e) {
                                // ignore
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }

        return $relatedIds;
    }

    private function findChildrenIds($file, $parentIds)
    {
        $handle = fopen($file, 'r');
        $children = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_posts`') !== false) {
                    $rows = mb_split("\),\(", $line); // Danger: simple splitting
                    foreach ($rows as $row) {
                        // Parse enough to get ID and Parent
                        // ID is 1st, Parent is usually 6th?
                        // Let's use regex to be safer about comma separation respecting strings
                        // Values: ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type

                        // We can try to extract ID and post_parent using regex finding the integers
                        // Pattern: (ID, author, dates..., parent, ... type)
                        // This is hard to regex robustly on one line.
                        // Let's search for "post_parent" values.

                        // Simplified parse: look for our parent IDs in the string, preceding a known post_type 'page'
                        foreach ($parentIds as $pid) {
                            // Look for ",pid," or ", pid," before 'page'
                            // This is slightly brittle.
                            // Alternative: Extract ID and Parent by loose numeric position matched with 'page'
                            if (strpos($row, "'page'") !== false && strpos($row, ",$pid,") !== false) {
                                // Extract ID (first number)
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
                            // Check if any of our childIds are in this group
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
        $data = [];
        $idsMap = array_flip($ids);
        $handle = fopen($file, 'r');

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'INSERT INTO `wp_posts`') !== false) {
                    // We need to parse strictly now to get Content and Title
                    // This is the hardest part with SQL dumps and regex.
                    // We will use a tokenizer equivalent.

                    // Splitting by `),(` is mostly safe for WP dumps unless content has it.
                    // WP escapes it as `\)\,\(` usually? No, it doesn't escape SQL syntax chars inside strings usually like that.
                    // But let's assume standard mysqldump format.

                    // Helper approach: find the IDs we want.
                    foreach ($ids as $id) {
                        if (strpos($line, "($id,") !== false || strpos($line, ",$id,") !== false) {
                            // potential match line
                            // Let's iterate the manual row split
                            $rows = explode('),(', $line);
                            foreach ($rows as $row) {
                                // Check ID at start
                                $row = trim($row, "(); \n\r");
                                if (preg_match("/^$id,/", $row)) {
                                    // Parse fields
                                    // We need Title (field ~5), Content (field ~4), Name (field ~11), GUID (field ~18 for image)
                                    // Parsing CSV-like structure respecting quotes...
                                    $fields = $this->parseSqlRow($row);

                                    // Map roughly based on WP 5.x schema
                                    // 0:ID, 1:post_author, 2:date, 3:date_gmt, 4:content, 5:title, 6:excerpt, 7:status, ... 11:name, ... 18:guid, ... 20:type
                                    if (isset($fields[4]) && isset($fields[5])) {
                                        $data[$id] = [
                                            'id' => $fields[0],
                                            'content' => $fields[4],
                                            'title' => $fields[5],
                                            'name' => $fields[11] ?? '',
                                            'guid' => $fields[18] ?? '', // Useful if it's an attachment, but these are pages.
                                            // We might need to parse content for images.
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
        // Very basic SQL value parser
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
            // Unescape SQL
            $val = str_replace("\\'", "'", $val);
            $val = str_replace('\\"', '"', $val);
            $val = str_replace('\\r', "\r", $val);
            $val = str_replace('\\n', "\n", $val);
        }

        return $val;
    }

    private function saveTreatments()
    {
        foreach ($this->treatmentGroups as $group) {
            $jsonName = [];
            $jsonSlug = [];
            $jsonDesc = [];
            $markets = [];

            // Collect data from each lang
            foreach ($group as $lang => $id) {
                if (! isset($this->posts[$id])) {
                    continue;
                }

                $post = $this->posts[$id];
                $cleanLang = $lang === 'en_US' ? 'en' : $lang; // Normalize if needed
                if ($cleanLang == 'us') {
                    $cleanLang = 'en';
                } // Map US to EN for content? Or separate?
                // User requirement:
                // es -> es, mx
                // en -> us
                // fr -> fr

                $jsonName[$cleanLang] = $post['title'];
                $jsonSlug[$cleanLang] = $post['name']; // post_name is slug
                $jsonDesc[$cleanLang] = $post['content'];

                // Markets logic
                if ($cleanLang === 'es') {
                    $markets[] = 'es';
                    $markets[] = 'mx';
                } elseif ($cleanLang === 'en' || $cleanLang === 'us') {
                    $markets[] = 'us';
                } elseif ($cleanLang === 'fr') {
                    $markets[] = 'fr';
                }
            }

            $markets = array_values(array_unique($markets));

            if (empty($jsonName)) {
                continue;
            }

            // Extract first image from description of the 'es' or first available version
            $firstContent = reset($jsonDesc);
            $imagePath = null;
            if (preg_match('/src="([^"]+)"/', $firstContent, $m)) {
                $imagePath = $m[1];
            }

            Treatment::updateOrCreate(
                ['slug->es' => $jsonSlug['es'] ?? (reset($jsonSlug))],
                [
                    'name' => $jsonName,
                    'slug' => $jsonSlug,
                    'description' => $jsonDesc, // This might be HUGE.
                    'available_markets' => $markets,
                    'published' => true,
                    'published_at' => now(),
                    'images' => $imagePath ? [$imagePath] : [],
                ]
            );

            $this->info('Imported treatment: '.(reset($jsonName)));
        }
    }
}
