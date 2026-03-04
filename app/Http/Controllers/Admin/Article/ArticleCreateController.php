<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class ArticleCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Articles/Create', [
            'apiUrl' => route('api.v1.articles.store'),
            'categoriesUrl' => route('api.v1.article-categories.list'),
        ]);
    }
}