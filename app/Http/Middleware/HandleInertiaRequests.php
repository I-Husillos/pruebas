<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Obtener el token Passport de la sesión si existe
        $apiToken = $request->session()->get('passport_token');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'apiToken' => $apiToken,
            'markets' => fn() => array_map(
                fn($m) => $m->toPrimitives(),
                $request->attributes->get('allMarkets') ?? []
            ),
            'languages' => fn() => array_map(
                fn($l) => $l->toPrimitives(),
                $request->attributes->get('allLanguages') ?? []
            ),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
