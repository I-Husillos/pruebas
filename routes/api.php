<?php

use App\Http\Controllers\API\V1\ProductCategory\ProductCategoriesGetController;
use App\Http\Controllers\API\V1\Product\ProductsGetController;
use App\Http\Controllers\API\V1\Treatment\TreatmentsGetController;
use App\Http\Controllers\API\V1\Treatment\TreatmentGetController;
use App\Http\Controllers\API\V1\Article\ArticlesGetController;
use App\Http\Controllers\API\V1\Article\ArticleGetController;
use App\Http\Controllers\API\V1\Page\PagesGetController;
use App\Http\Controllers\API\V1\Page\PageGetController;
use App\Http\Controllers\API\V1\Form\FormsGetController;
use App\Http\Controllers\API\V1\Form\FormSubmitController;
use App\Http\Controllers\API\V1\User\UsersGetController;
use App\Http\Controllers\API\V1\User\UserGetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/health-check', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
})->name('health-check');

// Form submission is public
Route::post('/v1/forms/{key}/submit', FormSubmitController::class)->name('forms.submit');

// API V1 - Protected by Passport
Route::prefix('v1')->name('v1.')->middleware(['auth:api'])->group(function () {

    // Catalog
    Route::get('/products', ProductsGetController::class)->name('products.list');
    Route::get('/products/{id}', \App\Http\Controllers\API\V1\Product\ProductGetController::class)->name('products.show');
    Route::post('/products', \App\Http\Controllers\API\V1\Product\ProductPostController::class)->name('products.store');
    Route::put('/products/reorder', \App\Http\Controllers\API\V1\Product\ProductReorderController::class)->name('products.reorder');
    Route::put('/products/{id}', \App\Http\Controllers\API\V1\Product\ProductPutController::class)->name('products.update');
    Route::delete('/products/{id}', \App\Http\Controllers\API\V1\Product\ProductDeleteController::class)->name('products.destroy');
    Route::get('/product-categories', \App\Http\Controllers\API\V1\ProductCategory\ProductCategoriesGetController::class)->name('product-categories.list');
    Route::post('/product-categories', \App\Http\Controllers\API\V1\ProductCategory\ProductCategoryPostController::class)->name('product-categories.store');
    Route::put('/product-categories/reorder', \App\Http\Controllers\API\V1\ProductCategory\ProductCategoryReorderController::class)->name('product-categories.reorder');
    Route::put('/product-categories/{id}', \App\Http\Controllers\API\V1\ProductCategory\ProductCategoryPutController::class)->name('product-categories.update');
    Route::delete('/product-categories/{id}', \App\Http\Controllers\API\V1\ProductCategory\ProductCategoryDeleteController::class)->name('product-categories.destroy');

    // Geo Targeting
    Route::get('/markets', \App\Http\Controllers\API\V1\Market\MarketsGetController::class)->name('markets.list');
    Route::get('/markets/{id}', \App\Http\Controllers\API\V1\Market\MarketGetController::class)->name('markets.show');
    Route::post('/markets', \App\Http\Controllers\API\V1\Market\MarketPostController::class)->name('markets.store');
    Route::put('/markets/{id}', \App\Http\Controllers\API\V1\Market\MarketPutController::class)->name('markets.update');
    Route::delete('/markets/{id}', \App\Http\Controllers\API\V1\Market\MarketDeleteController::class)->name('markets.destroy');
    Route::get('/languages', \App\Http\Controllers\API\V1\Language\LanguagesGetController::class)->name('languages.list');
    Route::get('/languages/{id}', \App\Http\Controllers\API\V1\Language\LanguageGetController::class)->name('languages.show');
    Route::post('/languages', \App\Http\Controllers\API\V1\Language\LanguagePostController::class)->name('languages.store');
    Route::put('/languages/{id}', \App\Http\Controllers\API\V1\Language\LanguagePutController::class)->name('languages.update');
    Route::delete('/languages/{id}', \App\Http\Controllers\API\V1\Language\LanguageDeleteController::class)->name('languages.destroy');

    // Treatments
    Route::get('/treatments', \App\Http\Controllers\API\V1\Treatment\TreatmentsGetController::class)->name('treatments.list');
    Route::get('/treatments/{id}', \App\Http\Controllers\API\V1\Treatment\TreatmentGetController::class)->name('treatments.show');
    Route::post('/treatments', \App\Http\Controllers\API\V1\Treatment\TreatmentPostController::class)->name('treatments.store');
    Route::put('/treatments/reorder', \App\Http\Controllers\API\V1\Treatment\TreatmentReorderController::class)->name('treatments.reorder');
    Route::put('/treatments/{id}', \App\Http\Controllers\API\V1\Treatment\TreatmentPutController::class)->name('treatments.update');
    Route::delete('/treatments/{id}', \App\Http\Controllers\API\V1\Treatment\TreatmentDeleteController::class)->name('treatments.destroy');
    Route::get('/treatment-categories', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoriesGetController::class)->name('treatment-categories.list');
    Route::get('/treatment-categories/{id}', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoryGetController::class)->name('treatment-categories.show');
    Route::post('/treatment-categories', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoryPostController::class)->name('treatment-categories.store');
    Route::put('/treatment-categories/reorder', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoryReorderController::class)->name('treatment-categories.reorder');
    Route::put('/treatment-categories/{id}', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoryPutController::class)->name('treatment-categories.update');
    Route::delete('/treatment-categories/{id}', \App\Http\Controllers\API\V1\TreatmentCategory\TreatmentCategoryDeleteController::class)->name('treatment-categories.destroy');
    Route::get('/treatments/{id}', \App\Http\Controllers\API\V1\Treatment\TreatmentGetController::class)->name('treatments.show');

    // Content
    Route::get('/articles', \App\Http\Controllers\API\V1\Article\ArticlesGetController::class)->name('articles.list');
    Route::get('/articles/{id}', \App\Http\Controllers\API\V1\Article\ArticleGetController::class)->name('articles.show');
    Route::post('/articles', \App\Http\Controllers\API\V1\Article\ArticlePostController::class)->name('articles.store');
    Route::put('/articles/reorder', \App\Http\Controllers\API\V1\Article\ArticleReorderController::class)->name('articles.reorder');
    Route::put('/articles/{id}', \App\Http\Controllers\API\V1\Article\ArticlePutController::class)->name('articles.update');
    Route::delete('/articles/{id}', \App\Http\Controllers\API\V1\Article\ArticleDeleteController::class)->name('articles.destroy');

    Route::get('/article-categories', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoriesGetController::class)->name('article-categories.list');
    Route::get('/article-categories/{id}', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoryGetController::class)->name('article-categories.show');
    Route::post('/article-categories', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoryPostController::class)->name('article-categories.store');
    Route::put('/article-categories/reorder', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoryReorderController::class)->name('article-categories.reorder');
    Route::put('/article-categories/{id}', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoryPutController::class)->name('article-categories.update');
    Route::delete('/article-categories/{id}', \App\Http\Controllers\API\V1\ArticleCategory\ArticleCategoryDeleteController::class)->name('article-categories.destroy');
    Route::get('/pages', \App\Http\Controllers\API\V1\Page\PagesGetController::class)->name('pages.list');
    Route::get('/pages/{id}', \App\Http\Controllers\API\V1\Page\PageGetController::class)->name('pages.show');
    Route::post('/pages', \App\Http\Controllers\API\V1\Page\PagePostController::class)->name('pages.store');
    Route::put('/pages/{id}', \App\Http\Controllers\API\V1\Page\PagePutController::class)->name('pages.update');
    Route::delete('/pages/{id}', \App\Http\Controllers\API\V1\Page\PageDeleteController::class)->name('pages.destroy');

    // Forms
    Route::get('/forms', \App\Http\Controllers\API\V1\Form\FormsGetController::class)->name('forms.list');
    Route::get('/forms/{id}', \App\Http\Controllers\API\V1\Form\FormGetController::class)->name('forms.show');
    Route::post('/forms', \App\Http\Controllers\API\V1\Form\FormPostController::class)->name('forms.store');
    Route::put('/forms/{id}', \App\Http\Controllers\API\V1\Form\FormPutController::class)->name('forms.update');
    Route::delete('/forms/{id}', \App\Http\Controllers\API\V1\Form\FormDeleteController::class)->name('forms.destroy');

    // Users
    Route::get('/users', \App\Http\Controllers\API\V1\User\UsersGetController::class)->name('users.list');
    Route::get('/users/{id}', \App\Http\Controllers\API\V1\User\UserGetController::class)->name('users.show');
    Route::post('/users', \App\Http\Controllers\API\V1\User\UserPostController::class)->name('users.store');
    Route::put('/users/{id}', \App\Http\Controllers\API\V1\User\UserPutController::class)->name('users.update');
    Route::delete('/users/{id}', \App\Http\Controllers\API\V1\User\UserDeleteController::class)->name('users.destroy');
    // Menus
    Route::post('/menus', \App\Http\Controllers\API\V1\Menu\MenuPostController::class)->name('menus.store');
    Route::put('/menus/{id}', \App\Http\Controllers\API\V1\Menu\MenuPutController::class)->name('menus.update');
    Route::delete('/menus/{id}', \App\Http\Controllers\API\V1\Menu\MenuDeleteController::class)->name('menus.destroy');
    Route::post('/menus/{menu}/items', \App\Http\Controllers\API\V1\Menu\MenuItemPostController::class)->name('menus.items.store');
    Route::put('/menus/items/{id}', \App\Http\Controllers\API\V1\Menu\MenuItemPutController::class)->name('menus.items.update');
    Route::delete('/menus/items/{id}', \App\Http\Controllers\API\V1\Menu\MenuItemDeleteController::class)->name('menus.items.destroy');
    Route::post('/menus/items/reorder', \App\Http\Controllers\API\V1\Menu\MenuItemsReorderController::class)->name('menus.items.reorder');

    // Widgets
    Route::post('/widgets', \App\Http\Controllers\API\V1\Widget\WidgetPostController::class)->name('widgets.store');
    Route::put('/widgets/{id}', \App\Http\Controllers\API\V1\Widget\WidgetPutController::class)->name('widgets.update');
    Route::delete('/widgets/{id}', \App\Http\Controllers\API\V1\Widget\WidgetDeleteController::class)->name('widgets.destroy');
    Route::post('/widgets/reorder', \App\Http\Controllers\API\V1\Widget\WidgetsReorderController::class)->name('widgets.reorder');

    // Media
    Route::post('/media', \App\Http\Controllers\API\V1\Media\MediaPostController::class)->name('media.store');

    // Change Control
    Route::post('/change-controls', \App\Http\Controllers\API\V1\ChangeControl\ChangeControlPostController::class)->name('change-controls.store');
    Route::put('/change-controls/{id}', \App\Http\Controllers\API\V1\ChangeControl\ChangeControlPutController::class)->name('change-controls.update');
    Route::delete('/change-controls/{id}', \App\Http\Controllers\API\V1\ChangeControl\ChangeControlDeleteController::class)->name('change-controls.destroy');
    Route::post('/change-controls/{id}/approve', \App\Http\Controllers\API\V1\ChangeControl\ChangeControlApproveController::class)->name('change-controls.approve');
    Route::post('/change-controls/{id}/reject', \App\Http\Controllers\API\V1\ChangeControl\ChangeControlRejectController::class)->name('change-controls.reject');
});

// Non-versioned API web routes
Route::get('menus/{menu}/items', [\App\Http\Controllers\API\MenuApiController::class, 'getItems'])->name('menus.items.get');
Route::get('widgets/zone/{zoneKey}', [\App\Http\Controllers\API\WidgetApiController::class, 'getByZone'])->name('widgets.zone.get');
Route::get('forms/{id}', \App\Http\Controllers\API\V1\Form\FormGetController::class)->name('api.forms.get')->name('forms.show');

// User info endpoint
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
})->name('user.info');
