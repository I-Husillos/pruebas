<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Response;
use Termosalud\Web\ArticleCategory\Application\Find\FindArticleCategoryByIdQuery;
use App\Models\Language;


final class ArticleCategoryEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $category = $this->queryBus->ask(new FindArticleCategoryByIdQuery($id));

        if (!$category) {
            abort(404);
        }

        $languages = Language::where('active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'native_name'])
            ->map(fn($l) => [
                'id'   => $l->id,
                'code' => $l->code,
                'name' => $l->native_name ?: $l->name,
            ])
            ->values();

        return $this->render('Admin/ArticleCategories/Edit', [
            'category'  => $category->toArray(),
            'languages' => $languages,
        ]);
    }
}
