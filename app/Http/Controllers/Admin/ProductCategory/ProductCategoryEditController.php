<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Response;
use Termosalud\Web\ProductCategory\Application\Find\FindProductCategoryQuery;

final class ProductCategoryEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Web\ProductCategory\Application\ProductCategoryResponse|null $category */
        $category = $this->queryBus->ask(new FindProductCategoryQuery($id));

        if (! $category) {
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

        return $this->render('Admin/ProductCategories/Edit', [
            'category' => $category->toArray(),
            'languages' => $languages,
        ]);
    }
}
