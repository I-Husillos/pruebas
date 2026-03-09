<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Infrastructure\Persistence;

use App\Models\TreatmentCategory as EloquentModel;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class EloquentTreatmentCategoryRepository extends EloquentRepository implements TreatmentCategoryRepository
{
    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(TreatmentCategory $category): void
    {
        $data = $category->toPrimitives();

        $id = $data['id'] ?? null;
        unset($data['id'], $data['created_at'], $data['updated_at']);

        $model = ($id !== null && $id > 0) ? $this->model->find($id) : null;

        if ($model) {
            $model->update($data);

            return;
        }

        $this->model->create($data);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria);
        $models = $query->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $countCriteria = new Criteria(
            $criteria->filters(),
            $criteria->order(),
            null,
            null
        );

        $eloquentCriteria = EloquentCriteriaConverter::convert($countCriteria);
        $query = $this->matching($eloquentCriteria);

        return $query->count();
    }

    public function search(int $id): ?TreatmentCategory
    {
        $model = $this->model->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function remove(int $id): void
    {
        $this->model->destroy($id);
    }

    private function toDomain(EloquentModel $model): TreatmentCategory
    {
        return new TreatmentCategory(
            $model->id,
            $model->name,
            $model->slug,
            $model->description,
            $model->active,
            (int) $model->sort_order,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString()
        );
    }
}
