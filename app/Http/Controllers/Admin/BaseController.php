<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
     * Obtiene el token de Passport del usuario autenticado.
     *
     * El token en texto plano sólo existe en el momento de su creación
     * (PersonalAccessTokenResult::$accessToken). En BD Passport almacena
     * únicamente el hash, así que lo guardamos en sesión al crearlos.
     */
    protected function getPassportToken(): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        // Primary source: token raw string stored in session at login time.
        $sessionToken = session('passport_token');
        if ($sessionToken) {
            return $sessionToken;
        }

        // Fallback: create a new token (e.g. after session expiry), cache it.
        if (method_exists($user, 'createToken')) {
            $result  = $user->createToken('backoffice');
            $rawToken = $result->accessToken;
            session(['passport_token' => $rawToken]);
            return $rawToken;
        }

        return null;
    }
}