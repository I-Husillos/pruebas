<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->name('api.')
            ->group(base_path('routes/api.php'));

        Route::middleware(['web', 'auth'])
            ->prefix('trmadmin')
            ->name('admin.')
            ->group(base_path('routes/backoffice.php'));

        Route::middleware('web')
            ->group(base_path('routes/frontoffice.php'));
    }
}
