<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

// Standard Breeze Auth & Profile Routes
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirect root to default market/language
Route::get('/', function () {
    return redirect()->route('home', ['market' => 'es', 'lang' => 'es']);
});

// Multi-market, multi-language routes (Catch-all prefix at the end)
Route::prefix('{market}/{lang}')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Products
    Route::get('productos', [ProductController::class, 'index'])
        ->name('products.index.es')
        ->where('lang', 'es');
    Route::get('products', [ProductController::class, 'index'])
        ->name('products.index.en')
        ->where('lang', 'en');

    // Product categories
    Route::get('productos/categoria/{categorySlug}', [ProductController::class, 'index'])
        ->name('products.category.es')
        ->where('lang', 'es');
    Route::get('products/category/{categorySlug}', [ProductController::class, 'index'])
        ->name('products.category.en')
        ->where('lang', 'en');

    Route::get('productos/{slug}', [ProductController::class, 'show'])
        ->name('products.show.es')
        ->where('lang', 'es');
    Route::get('products/{slug}', [ProductController::class, 'show'])
        ->name('products.show.en')
        ->where('lang', 'en');

    // Treatments
    Route::get('tratamientos', [\App\Http\Controllers\Web\TreatmentController::class, 'index'])
        ->name('treatments.index.es')
        ->where('lang', 'es');
    Route::get('treatments', [\App\Http\Controllers\Web\TreatmentController::class, 'index'])
        ->name('treatments.index.en')
        ->where('lang', 'en');

    Route::get('tratamientos/{slug}', [\App\Http\Controllers\Web\TreatmentController::class, 'show'])
        ->name('treatments.show.es')
        ->where('lang', 'es');

    Route::get('treatments/{slug}', [\App\Http\Controllers\Web\TreatmentController::class, 'show'])
        ->name('treatments.show.en')
        ->where('lang', 'en');

    // Custom Landings (catch-all for pages if not matched above, or specific prefix)
    Route::get('p/{slug}', [\App\Http\Controllers\Web\PageController::class, 'show'])
        ->name('pages.show');

    // Public Form Page
    Route::get('forms/{key}', [\App\Http\Controllers\Web\FormController::class, 'show'])
        ->name('forms.show');
});
