<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\ArticleCategory;

final class ArticleCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Articles/Create', [
            'categories' => ArticleCategory::all()
        ]);
    }
}