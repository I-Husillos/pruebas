<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;
use Inertia\Response;
use Termosalud\Web\Article\Application\Find\FindArticleByIdQuery;

final class ArticleEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $article = $this->queryBus->ask(new FindArticleByIdQuery($id));
        
        if(! $article) {
            abort(404);
        }

        return Inertia::render('Admin/Articles/Edit', [
            'article' => $article->toArray(),
            'categories' => \App\Models\ArticleCategory::all(['id', 'name'])
        ]);
    }
}
