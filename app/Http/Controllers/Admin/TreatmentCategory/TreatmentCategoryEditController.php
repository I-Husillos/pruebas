<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
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

        $languages = Language::where('active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'native_name'])
            ->map(fn ($l) => [
                'id'   => $l->id,
                'code' => $l->code,
                'name' => $l->native_name ?: $l->name,
            ])
            ->values();

        return $this->render('Admin/TreatmentCategories/Edit', [
            'category'  => $category->toArray(),
            'languages' => $languages,
        ]);
    }
}
