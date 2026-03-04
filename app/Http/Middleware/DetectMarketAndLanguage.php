<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;

class DetectMarketAndLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the route has parameters or is not the root, likely already localized
        if ($request->route('market') && $request->route('lang')) {
            return $next($request);
        }

        // Only redirect root or specific routes
        if ($request->path() !== '/') {
            return $next($request);
        }

        // 1. Detect Country
        $ip = $request->ip();
        // In local mock IP if needed
        if ($ip === '127.0.0.1') {
            $ip = '8.8.8.8'; // US
        }

        $position = Location::get($ip);
        $countryCode = $position ? $position->countryCode : 'ES'; // Default ES

        // 2. Map Country to Market
        // Default mapping: ES -> ES, others -> default (based on active markets)
        // Ideally fetch from DB markets to find match
        $market = DB::table('markets')->where('code', $countryCode)->first();

        $targetMarket = $market ? $market->code : 'ES'; // Fallback to ES market
        if (! $market) {
            // Check if we have a "Global" market or just fallback?
            // Fallback to highest priority market
            $defaultMarket = DB::table('markets')->where('active', true)->orderBy('priority')->first();
            $targetMarket = $defaultMarket ? $defaultMarket->code : 'ES';
        }

        // 3. Detect Language
        $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        // Check if language is supported
        $language = DB::table('languages')->where('code', $browserLang)->where('active', true)->first();
        $targetLang = $language ? $browserLang : 'es'; // Fallback to es

        return redirect()->route('home', ['market' => $targetMarket, 'lang' => $targetLang]);
    }
}
