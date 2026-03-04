<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(string $market, string $lang, ?string $categorySlug = null): Response
    {
        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        // Get all categories for filter menu
        $categories = DB::table('product_categories')
            ->where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => json_decode($cat->name, true),
                    'slug' => json_decode($cat->slug, true),
                ];
            });

        // Find selected category if slug provided
        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = DB::table('product_categories')
                ->whereRaw('JSON_EXTRACT(slug, ?) = ?', ["$.{$lang}", $categorySlug])
                ->first();
        }

        $query = DB::table('products')
            ->where('published', true)
            ->whereRaw('JSON_CONTAINS(available_markets, ?)', [json_encode($market)]);

        // Filter by category if selected
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        $cacheKey = "products.index.{$market}.{$lang}." . ($categorySlug ?? 'all');

        $products = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($query) {
            return $query->orderBy('sort_order')->get();
        })->map(function ($product) {
            return [
                'id' => $product->id,
                'code' => $product->code,
                'name' => json_decode($product->name, true),
                'slug' => json_decode($product->slug, true),
                'short_description' => json_decode($product->short_description, true),
                'images' => json_decode($product->images, true),
                'category' => $product->category,
            ];
        });

        return Inertia::render('Products/Index', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory ? [
                'id' => $selectedCategory->id,
                'name' => json_decode($selectedCategory->name, true),
                'slug' => json_decode($selectedCategory->slug, true),
            ] : null,
        ]);
    }

    public function show(string $market, string $lang, string $slug): Response
    {
        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        $cacheKey = "products.show.{$lang}.{$slug}";

        $product = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($lang, $slug) {
            return DB::table('products')
                ->where('published', true)
                ->where("slug->$lang", $slug)
                ->first();
        });

        if (! $product) {
            abort(404);
        }

        $product = [
            'id' => $product->id,
            'code' => $product->code,
            'name' => json_decode($product->name, true),
            'slug' => json_decode($product->slug, true),
            'short_description' => json_decode($product->short_description, true),
            'description' => json_decode($product->description, true),
            'blocks_json' => (function () use ($product, $lang) {
                $blocks = json_decode($product->blocks_json, true) ?? [];
                return isset($blocks[$lang]) ? $blocks[$lang] : $blocks;
            })(),
            'technical_specs' => json_decode($product->technical_specs, true),
            'images' => json_decode($product->images, true),
            'category' => $product->category,
            'tags' => json_decode($product->tags, true),
            'meta_title' => json_decode($product->meta_title, true),
            'meta_description' => json_decode($product->meta_description, true),
        ];

        return Inertia::render('Products/Show', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'product' => $product,
            'edit_url' => route('admin.products.edit', $product['id']),
        ]);
    }
}
