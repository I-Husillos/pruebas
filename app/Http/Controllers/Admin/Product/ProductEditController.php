<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;
use Termosalud\Web\Product\Application\Find\FindProductQuery;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;

final class ProductEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $product = $this->queryBus->ask(new FindProductQuery($id));

        if (! $product) {
            abort(404);
        }

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product->toArray(),
            'categories' => \App\Models\ProductCategory::all(['id', 'name']),
            'markets'    => \App\Models\Market::where('active', true)->get(['id', 'code', 'name']),
            'forms'      => \App\Models\Form::all(['id', 'name']),
        ]);
    }
}
