<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentController extends Controller
{
    public function index(string $market, string $lang): Response
    {
        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        $cacheKey = "treatments.index.{$market}.{$lang}";

        $treatments = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () {
            return DB::table('treatments')
                ->where('published', true)
                ->orderBy('sort_order')
                ->get();
        })->map(function ($treatment) {
            return [
                'id' => $treatment->id,
                'name' => json_decode($treatment->name, true),
                'slug' => json_decode($treatment->slug, true),
                'short_description' => json_decode($treatment->description, true), // Fallback to description if short_does not exist, or just use description
                // Add other fields if necessary
            ];
        });

        return Inertia::render('Treatments/Index', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'treatments' => $treatments,
            'treatments' => $treatments,
        ]);
    }

    public function show(string $market, string $lang, string $slug): Response
    {
        // Find treatment where slug->$lang matches
        $cacheKey = "treatments.show.{$lang}.{$slug}";

        $treatment = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($lang, $slug) {
            return DB::table('treatments')
                ->where('published', true)
                ->where("slug->$lang", $slug)
                ->first();
        });

        if (! $treatment) {
            abort(404);
        }

        // Decode JSON fields
        $treatment->name = json_decode($treatment->name, true);
        $treatment->slug = json_decode($treatment->slug, true);
        $treatment->description = json_decode($treatment->description, true);
        $blocks = json_decode($treatment->blocks_json, true) ?? [];
        if (isset($blocks[$lang])) {
            $treatment->blocks_json = $blocks[$lang];
        } else {
            $treatment->blocks_json = $blocks;
        }
        $treatment->images = json_decode($treatment->images, true);
        $treatment->meta_title = json_decode($treatment->meta_title, true);
        $treatment->meta_description = json_decode($treatment->meta_description, true);

        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        return Inertia::render('Treatments/Show', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'treatment' => $treatment,
            'edit_url' => route('admin.treatments.edit', $treatment->id),
        ]);
    }
}
