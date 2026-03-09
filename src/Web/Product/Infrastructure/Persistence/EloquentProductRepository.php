<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Infrastructure\Persistence;

use App\Models\Product as ProductEloquentModel;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentProductRepository extends EloquentRepository implements ProductRepository
{
    public function __construct(ProductEloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(Product $product): void
    {
        $data = $product->toPrimitives();
        $id = $data['id'] ?? null;

        $model = $id ? $this->model->find($id) : null;

        if ($model) {
            $model->update($data);

            return;
        }

        $this->model->create($data);
    }

    public function search(int $id): ?Product
    {
        $model = $this->model->find($id);

        return $model ? $this->toDomain($model) : null;
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

    private function toDomain(ProductEloquentModel $model): Product
    {
        return Product::fromPrimitives($model->toArray());
    }

    public function remove(int $id): void
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }
}
