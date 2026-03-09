<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Infrastructure\Persistence;

use App\Models\Treatment as TreatmentEloquentModel;
use Termosalud\Web\Treatment\Domain\Treatment;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentTreatmentRepository extends EloquentRepository implements TreatmentRepository
{
    public function __construct(TreatmentEloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(Treatment $treatment): void
    {
        $data = $treatment->toPrimitives();

        if (empty($data['id'])) {
            unset($data['id']);
            $this->model->create($data);
        } else {
            $model = $this->model->find($data['id']);

            if ($model) {
                $model->update($data);

                return;
            }

            $this->model->create($data);
        }
    }

    public function search(int $id): ?Treatment
    {
        $model = $this->model->find($id);

        if (! $model) {
            return null;
        }

        return Treatment::fromPrimitives($model->toArray());
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
        // Create criteria without pagination for counting
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
    public function toDomain(TreatmentEloquentModel $model): Treatment
    {
        return Treatment::fromPrimitives($model->toArray());
    }


    public function remove(int $id): void
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }
}
