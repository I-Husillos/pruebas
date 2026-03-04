<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ArticleCategoriesIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/ArticleCategories/Index', [
            'apiUrl' => route('api.v1.article-categories.list'),
        ]);
    }
}
