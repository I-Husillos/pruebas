<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Infrastructure\Persistence;

use App\Models\ProductCategory as ProductCategoryEloquentModel;
use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryId;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentProductCategoryRepository extends EloquentRepository implements ProductCategoryRepository
{
    public function __construct(ProductCategoryEloquentModel $model)
    {
        $this->model = $model;
    }

    public function search(ProductCategoryId $id): ?ProductCategory
    {
        $model = $this->model->find($id->value());

        if (! $model) {
            return null;
        }

        return ProductCategory::fromPrimitives($model->toArray());
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria);
        $models = $query->get();

        return array_map(
            fn($model) => ProductCategory::fromPrimitives($model->toArray()),
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
}
