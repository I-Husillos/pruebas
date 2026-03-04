<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use Illuminate\Http\Request;
use Inertia\Response;
use App\Http\Controllers\Admin\BaseController;

final class ArticlesIndexController extends BaseController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('Admin/Articles/Index', [
            'apiUrl' => route('api.v1.articles.list'),
        ]);
    }
}
