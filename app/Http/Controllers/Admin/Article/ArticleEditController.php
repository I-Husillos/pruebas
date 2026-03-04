<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleEditController extends BaseController
{
    public function __invoke(int $id): Response
    {
        return $this->render('Admin/Articles/Edit', [
            'apiUrl' => route('api.v1.articles.update', $id),
            'categoriesUrl' => route('api.v1.article-categories.list'),
        ]);
    }
}
