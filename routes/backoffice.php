<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

// Markets Management
Route::get('markets', \App\Http\Controllers\Admin\Market\MarketsIndexController::class)->name('markets.index');
Route::get('markets/create', \App\Http\Controllers\Admin\Market\MarketCreateController::class)->name('markets.create');
Route::get('markets/{id}/edit', \App\Http\Controllers\Admin\Market\MarketEditController::class)->name('markets.edit');

// Content Management
Route::get('articles', \App\Http\Controllers\Admin\Article\ArticlesIndexController::class)->name('articles.index');
Route::get('articles/create', \App\Http\Controllers\Admin\Article\ArticleCreateController::class)->name('articles.create');
Route::get('articles/{id}/edit', \App\Http\Controllers\Admin\Article\ArticleEditController::class)->name('articles.edit');

// Products Management
Route::get('products', \App\Http\Controllers\Admin\Product\ProductsIndexController::class)->name('products.index');
Route::get('products/create', \App\Http\Controllers\Admin\Product\ProductCreateController::class)->name('products.create');
Route::get('products/{id}/edit', \App\Http\Controllers\Admin\Product\ProductEditController::class)->name('products.edit');

// Languages Management
Route::get('languages', \App\Http\Controllers\Admin\Language\LanguagesIndexController::class)->name('languages.index');
Route::get('languages/create', \App\Http\Controllers\Admin\Language\LanguageCreateController::class)->name('languages.create');
Route::get('languages/{id}/edit', \App\Http\Controllers\Admin\Language\LanguageEditController::class)->name('languages.edit');

// Treatments Management
Route::get('treatments', \App\Http\Controllers\Admin\Treatment\TreatmentsIndexController::class)->name('treatments.index');
Route::get('treatments/create', \App\Http\Controllers\Admin\Treatment\TreatmentCreateController::class)->name('treatments.create');
Route::get('treatments/{id}/edit', \App\Http\Controllers\Admin\Treatment\TreatmentEditController::class)->name('treatments.edit');

// Page Management (Unified Pages)
Route::get('pages', \App\Http\Controllers\Admin\Page\PagesIndexController::class)->name('pages.index');
Route::get('pages/create', \App\Http\Controllers\Admin\Page\PageCreateController::class)->name('pages.create');
Route::get('pages/{id}/edit', \App\Http\Controllers\Admin\Page\PageEditController::class)->name('pages.edit');

// User Management
Route::get('users', \App\Http\Controllers\Admin\User\UsersIndexController::class)->name('users.index');
Route::get('users/create', \App\Http\Controllers\Admin\User\UserCreateController::class)->name('users.create');
Route::get('users/{id}/edit', \App\Http\Controllers\Admin\User\UserEditController::class)->name('users.edit');

// Form Management
Route::get('forms', \App\Http\Controllers\Admin\Form\FormsIndexController::class)->name('forms.index');
Route::get('forms/create', \App\Http\Controllers\Admin\Form\FormCreateController::class)->name('forms.create');
Route::get('forms/{id}/edit', \App\Http\Controllers\Admin\Form\FormEditController::class)->name('forms.edit');
Route::get('forms/{id}/submissions', \App\Http\Controllers\Admin\Form\FormSubmissionsIndexController::class)->name('forms.submissions.index');

// Media Management
Route::get('media', \App\Http\Controllers\Admin\Media\MediaIndexController::class)->name('media.index');
Route::post('media/upload', \App\Http\Controllers\API\V1\Media\MediaPostController::class)->name('media.store');

// Article Category Management
Route::get('article-categories', \App\Http\Controllers\Admin\ArticleCategory\ArticleCategoriesIndexController::class)->name('article-categories.index');
Route::get('article-categories/create', \App\Http\Controllers\Admin\ArticleCategory\ArticleCategoryCreateController::class)->name('article-categories.create');
Route::get('article-categories/{id}/edit', \App\Http\Controllers\Admin\ArticleCategory\ArticleCategoryEditController::class)->name('article-categories.edit');

// Product Category Management
Route::get('product-categories', \App\Http\Controllers\Admin\ProductCategory\ProductCategoriesIndexController::class)->name('product-categories.index');
Route::get('product-categories/create', \App\Http\Controllers\Admin\ProductCategory\ProductCategoryCreateController::class)->name('product-categories.create');
Route::get('product-categories/{id}/edit', \App\Http\Controllers\Admin\ProductCategory\ProductCategoryEditController::class)->name('product-categories.edit');

// Treatment Category Management
Route::get('treatment-categories', \App\Http\Controllers\Admin\TreatmentCategory\TreatmentCategoriesIndexController::class)->name('treatment-categories.index');
Route::get('treatment-categories/create', \App\Http\Controllers\Admin\TreatmentCategory\TreatmentCategoryCreateController::class)->name('treatment-categories.create');
Route::get('treatment-categories/{id}/edit', \App\Http\Controllers\Admin\TreatmentCategory\TreatmentCategoryEditController::class)->name('treatment-categories.edit');

// Menu & Navigation
Route::get('menus', \App\Http\Controllers\Admin\Menu\MenusIndexController::class)->name('menus.index');
Route::get('menus/create', \App\Http\Controllers\Admin\Menu\MenuCreateController::class)->name('menus.create');
Route::get('menus/{id}/edit', \App\Http\Controllers\Admin\Menu\MenuEditController::class)->name('menus.edit');
Route::get('menus/{menu}/items', \App\Http\Controllers\Admin\Menu\MenuItemsIndexController::class)->name('menus.items.index');
Route::get('menus/{menu}/items/create', \App\Http\Controllers\Admin\Menu\MenuItemCreateController::class)->name('menus.items.create');
Route::get('menus/items/{id}/edit', \App\Http\Controllers\Admin\Menu\MenuItemEditController::class)->name('menus.items.edit');

// Widget Management
Route::get('widgets', \App\Http\Controllers\Admin\Widget\WidgetsIndexController::class)->name('widgets.index');
Route::get('widgets/create', \App\Http\Controllers\Admin\Widget\WidgetCreateController::class)->name('widgets.create');
Route::get('widgets/{id}/edit', \App\Http\Controllers\Admin\Widget\WidgetEditController::class)->name('widgets.edit');

// Change Control Management
Route::get('change-controls', \App\Http\Controllers\Admin\ChangeControl\ChangeControlsIndexController::class)->name('change-controls.index');
Route::get('change-controls/create', \App\Http\Controllers\Admin\ChangeControl\ChangeControlCreateController::class)->name('change-controls.create');
Route::get('change-controls/{id}/edit', \App\Http\Controllers\Admin\ChangeControl\ChangeControlEditController::class)->name('change-controls.edit');
