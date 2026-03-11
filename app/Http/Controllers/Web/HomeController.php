<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(string $market, string $lang): Response
    {
        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        $featuredProducts = DB::table('products')
            ->where('status', true)
            ->limit(6)
            ->get()

            ->map(function ($product) use ($lang) {
                $name = json_decode($product->name, true);
                $slug = json_decode($product->slug, true);
                $shortDescription = json_decode($product->short_description, true);
                $images = json_decode($product->images, true);

                // Ensure translation exists for current lang
                if (! isset($name[$lang]) || ! isset($slug[$lang])) {
                    return null;
                }

                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $name,
                    'slug' => $slug,
                    'short_description' => $shortDescription,
                    'images' => $images,
                ];
            })
            ->filter()
            ->values();

        return Inertia::render('Home', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
