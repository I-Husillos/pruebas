<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Infrastructure\Persistence;

use App\Models\ArticleCategory as EloquentModel;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategory;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentArticleCategoryRepository extends EloquentRepository implements ArticleCategoryRepository
{
    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
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

    public function findById(int $id): ?ArticleCategory
    {
        $model = $this->model->find($id);

        return $model ? ArticleCategory::fromPrimitives($model->toArray()) : null;
    }

    public function findBySlug(string $slug, string $locale): ?ArticleCategory
    {
        $model = $this->model->where('slug', $slug)->where('locale', $locale)->first();

        return $model ? ArticleCategory::fromPrimitives($model->toArray()) : null;
    }

    private function toDomain(EloquentModel $model): ArticleCategory
    {
        return new ArticleCategory(
            $model->id,
            $model->name,
            $model->slug,
            $model->description,
            $model->active,
            (int)$model->sortOrder,
            $model->createdAt,
            $model->updatedAt
        );
    }
}
