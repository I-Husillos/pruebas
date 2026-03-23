<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\FrontController;
use Illuminate\Support\Facades\Route;

// Auth y Perfil
require __DIR__ . '/auth.php';

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Redirige la raíz al market/lang por defecto
// Route::get('/', function () {
//     return redirect()->route('home', ['market' => 'es', 'lang' => 'es']);
// });

// Frontoffice: multi-market / multi-language
//
// Filosofía de URLs:
//   /{market}/{lang}                   → home del mercado/idioma
//   /{market}/{lang}/{slug}            → página resuelta por SlugResolver
//   /{market}/{lang}/{slug}/{extra}    → ídem con filtros y/o paginación
//
// Formato de {extra}:
//   Pares "clave-valor" separados por '_'. No usa '/' internamente.
//   Ejemplos:
//     pagina-2
//     precio-100-500_pagina-2
//     orden-precio-asc_marca-laser_pagina-3
//
// El middleware 'load.market' y 'load.language' cargan las entidades de dominio
// Market y Language desde la base de datos y las inyectan en los atributos
// de la request para que el FrontController las consuma.
//
Route::prefix('{market}/{lang}')
    ->where(['market' => '[a-z]{2}', 'lang' => '[a-z]{2}'])
    ->middleware(['load.market', 'load.language'])
    ->group(function () {
        Route::get('/', [FrontController::class, 'pages'])->name('home');

        
        // Catch-all: slug + extra opcional (filtros / paginación en un solo segmento separado por '_')
        // Ejemplo: /es/es/cirugia-estetica/precio-100-500_pagina-2
        // Route::get('{slug}/{extra?}', FrontController::class)
        //     ->name('front.show')
        //     ->where('slug', '[a-z0-9][a-z0-9\-]*')
        //     ->where('extra', '[a-z0-9][a-z0-9\-\_]*');
    });

