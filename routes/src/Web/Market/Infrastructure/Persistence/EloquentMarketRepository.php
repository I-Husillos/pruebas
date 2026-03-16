<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Infrastructure\Persistence;

use App\Models\Market as MarketEloquentModel;
use Termosalud\Web\Market\Domain\Market;
use Termosalud\Web\Market\Domain\MarketRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;

final class EloquentMarketRepository extends EloquentRepository implements MarketRepository
{
    public function __construct(MarketEloquentModel $model) 
    {
            $this->model = $model;
    }

    public function save(Market $market): void
    {
        $data = $market->toPrimitives();
        $id = $data['id'] ?? null;

        unset($data['id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);

        $model = $id ? $this->model->newQuery()->find($id) : null;

        if (! $model) {
            $model = $this->model->newQuery()
                ->where('code', $data['code'])
                ->first();
        }

        if ($model) {
            $model->update($data);

            return;
        }

        $this->model->newQuery()->create($data);
    }

    public function search(int $id): ?Market
    {
        $model = $this->model->newQuery()->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function remove(int $id): void
    {
        $model = $this->model->newQuery()->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }

    public function findByCode(string $code): ?Market
    {
        $model = $this->model->newQuery()
            ->where('code', $code)
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAllActive(): array
    {
        $models = $this->model->newQuery()
            ->where('active', true)
            ->orderBy('priority')
            ->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
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

    private function toDomain(MarketEloquentModel $model): Market
    {
        return Market::fromPrimitives($model->toArray());
    }

}
