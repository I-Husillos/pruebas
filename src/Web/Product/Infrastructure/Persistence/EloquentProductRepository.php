<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Infrastructure\Persistence;

use App\Models\Product as ProductEloquentModel;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductId;
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

        $this->updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    public function search(ProductId $id): ?Product
    {
        $model = $this->model->find($id->value());

        if (! $model) {
            return null;
        }

        return Product::fromPrimitives($model->toArray());
    }

    public function searchAll(): array
    {
        $models = $this->model::all();

        return array_map(
            fn($model) => Product::fromPrimitives($model->toArray()),
            $models->toArray()
        );
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria);
        $models = $query->get();

        return array_map(
            fn($model) => Product::fromPrimitives($model->toArray()),
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

    public function remove(ProductId $id): void
    {
        $this->model->destroy($id->value());
    }
}
