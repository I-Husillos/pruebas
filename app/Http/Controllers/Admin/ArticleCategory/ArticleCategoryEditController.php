<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleCategoryEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $category = ArticleCategory::findOrFail($id);

        return Inertia::render('Admin/ArticleCategories/Edit', [
            'category' => $category,
        ]);
    }
}
