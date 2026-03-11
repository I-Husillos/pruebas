<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Controller;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;
use Inertia\Response;
use Termosalud\Web\ArticleCategory\Application\Find\FindArticleCategoryByIdQuery;

final class ArticleCategoryEditController extends Controller
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $category = $this->queryBus->ask(new FindArticleCategoryByIdQuery($id));
        
        if(! $category) {
            abort(404);
        }

        return Inertia::render('Admin/ArticleCategories/Edit', [
            'category' => $category->toArray(),
        ]);
    }
}
