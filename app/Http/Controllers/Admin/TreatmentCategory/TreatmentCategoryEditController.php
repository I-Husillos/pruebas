<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Response;
use Termosalud\Web\TreatmentCategory\Application\Find\FindTreatmentCategoryByIdQuery;

final class TreatmentCategoryEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $category = $this->queryBus->ask(new FindTreatmentCategoryByIdQuery($id));


        if (! $category) {
            abort(404);
        }

        return $this->render('Admin/TreatmentCategories/Edit', [
            'category' => $category->toArray(),
        ]);
    }
}
