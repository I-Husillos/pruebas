<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

abstract class BaseController extends Controller
{
    /**
     * Renderiza una vista Inertia con el token de Passport
     */
    protected function render(string $component, array $props = []): Response
    {
        $token = $this->getPassportToken();

        return Inertia::render($component, array_merge([
            'apiToken' => $token,
        ], $props));
    }

    /**
     * Obtiene el token de Passport del usuario autenticado
     */
    protected function getPassportToken(): ?string
    {
        return auth()->user()?->tokens()
            ->latest('created_at')
            ->first()?->accessToken;
    }
}