<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Controller;
use App\Models\TreatmentCategory;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;
use Inertia\Response;
use Termosalud\Web\TreatmentCategory\Application\Find\FindTreatmentCategoryByIdQuery;

final class TreatmentCategoryEditController extends Controller
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $category = $this->queryBus->ask(new FindTreatmentCategoryByIdQuery($id));


        if (! $category) {
            abort(404);
        }

        return Inertia::render('Admin/TreatmentCategories/Edit', [
            'category' => $category->toArray(),
        ]);
    }
}
