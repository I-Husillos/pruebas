<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleCategoryCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/ArticleCategories/Create');
    }
}
