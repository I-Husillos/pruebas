<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Product;
use App\Models\Treatment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.xml file';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Static pages (Localized)
        // We have markets and languages.
        // We should add home pages for all valid combinations.
        $markets = DB::table('markets')->where('active', true)->get();
        // Since home route is /{market}/{lang}, we loop through all logic.
        // Actually, easiest is to just add known pages + dynamic content.

        // Products
        $products = Product::where('published', true)->get();
        foreach ($products as $product) {
            $slugs = $product->slug; // JSON
            $avail = $product->available_markets; // JSON array of market codes

            // We need to form URLs: /{market}/{lang}/products/{slug}
            // Logic: For each market in available_markets
            //   For each language supported by that market (we need a map)
            //     Add URL

            // This is complex because we need the Market->Languages relationship.
            // Let's rely on basic data:
            if (is_array($avail)) {
                foreach ($avail as $marketCode) {
                    $market = $markets->firstWhere('code', $marketCode);
                    if (! $market) {
                        continue;
                    }

                    // We need languages for this market.
                    // Assuming all active languages for now OR we query market_languages table
                    // Simplified: Add for all languages that have a slug translation.
                    if (is_array($slugs)) {
                        foreach ($slugs as $lang => $slug) {
                            $sitemap->add(Url::create("/{$marketCode}/{$lang}/products/{$slug}")
                                ->setLastModificationDate($product->updated_at));
                        }
                    }
                }
            }
        }
        $this->info('Products added.');

        // Treatments
        $treatments = Treatment::where('published', true)->get();
        foreach ($treatments as $treatment) {
            $slugs = $treatment->slug; // JSON
            $avail = $treatment->available_markets; // JSON array

            if (is_array($avail)) {
                foreach ($avail as $marketCode) {
                    if (is_array($slugs)) {
                        foreach ($slugs as $lang => $slug) {
                            $sitemap->add(Url::create("/{$marketCode}/{$lang}/treatments/{$slug}")
                                ->setLastModificationDate($treatment->updated_at));
                        }
                    }
                }
            }
        }
        $this->info('Treatments added.');

        // Pages (Custom Landings)
        // Table: pages (id, market_code, language_code, slug)
        $pages = Page::where('is_active', true)->get();
        foreach ($pages as $page) {
            $sitemap->add(Url::create("/{$page->market_code}/{$page->language_code}/p/{$page->slug}")
                ->setLastModificationDate($page->updated_at));
        }
        $this->info('Pages added.');

        // Save
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return 0;
    }
}
