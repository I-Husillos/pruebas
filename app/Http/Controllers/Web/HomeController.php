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
        $market = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $language = DB::table('languages')->where('active', true)->get();

        return Inertia::render('Home', [
            'market' => $market,
            'lang' => $lang,
            'language' => $language,
        ]);
    }
}
