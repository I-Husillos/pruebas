<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Infrastructure\Persistence;

use App\Models\Treatment as TreatmentEloquentModel;
use Termosalud\Web\Treatment\Domain\Treatment;
use Termosalud\Web\Treatment\Domain\TreatmentId;
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

        $this->updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    public function search(TreatmentId $id): ?Treatment
    {
        $model = $this->model->find($id->value());

        if (! $model) {
            return null;
        }

        return Treatment::fromPrimitives($model->toArray());
    }

    public function searchAll(): array
    {
        $models = $this->model::all();

        return array_map(
            fn($model) => Treatment::fromPrimitives($model->toArray()),
            $models->toArray()
        );
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria);
        $models = $query->get();

        return array_map(
            fn($model) => Treatment::fromPrimitives($model->toArray()),
            $models->toArray()
        );
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

    public function remove(TreatmentId $id): void
    {
        $this->model->destroy($id->value());
    }
}
