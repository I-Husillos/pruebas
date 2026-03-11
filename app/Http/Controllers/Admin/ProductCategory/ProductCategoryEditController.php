<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Http\Controllers\Controller;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;
use Inertia\Response;
use Termosalud\Web\ProductCategory\Application\Find\FindProductCategoryQuery;

final class ProductCategoryEditController extends Controller
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Web\ProductCategory\Application\ProductCategoryResponse|null $category */
        $category = $this->queryBus->ask(new FindProductCategoryQuery($id));

        if (! $category) {
            abort(404);
        }

        return Inertia::render('Admin/ProductCategories/Edit', [
            'category' => $category->toArray(),
        ]);
    }
}
