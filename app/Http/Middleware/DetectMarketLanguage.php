<?php

namespace App\Http\Middleware;

use App\Models\Market;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class DetectMarketLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for internal routes or assets
        if ($request->is('api/*') ||
            $request->is('trmadmin*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('logout') ||
            $request->is('forgot-password') ||
            $request->is('reset-password/*') ||
            $request->is('verify-email/*') ||
            $request->is('confirm-password') ||
            $request->is('profile') ||
            $request->is('_debugbar/*') ||
            $request->is('sanctum/*')) {
            return $next($request);
        }

        $marketCode = $request->route('market');
        $langCode = $request->route('lang');

        // If parameters are missing (e.g. root URL not captured by prefix), let the routes logic handle redirect
        if (! $marketCode || ! $langCode) {
            return $next($request);
        }

        // Cache market validation for performance (60 minutes)
        // Use 'file' cache driver or 'redis' depending on .env, ensure cache works
        // We use a simple key
        $market = Cache::remember("market_code_{$marketCode}", 3600, function () use ($marketCode) {
            return Market::where('code', $marketCode)
                ->where('active', true)
                ->first();
        });

        if (! $market) {
            abort(404, "Market '{$marketCode}' not found.");
        }

        // Validate Language within Market (check JSON array)
        // casting ensures we treat it as array
        $enabledLanguages = $market->enabled_languages ?? [];

        if (! in_array($langCode, $enabledLanguages)) {
            // Language not allowed -> Redirect to default
            $defaultLang = $market->default_language;

            // We can't easily regenerate the current named route if we are in a middleware before route matching is fully done or if params are wrong.
            // But usually this middleware runs after routing.
            // Let's just do a string replace on the segment for simplicity
            $currentUri = $request->getRequestUri();
            // Replace /market/wrong -> /market/right
            // Pattern: /market/lang/...
            $newUri = preg_replace("/^\/{$marketCode}\/{$langCode}/", "/{$marketCode}/{$defaultLang}", $currentUri);

            return redirect()->to($newUri);
        }

        // Set Application Locale
        App::setLocale($langCode);

        // Share defaults with Views/URL generator
        URL::defaults(['market' => $marketCode, 'lang' => $langCode]);

        // Add attributes to request for Controllers/Inertia
        $request->attributes->add(['current_market' => $market, 'current_lang' => $langCode]);

        return $next($request);
    }
}
