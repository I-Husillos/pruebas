<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Infrastructure\Persistence;

use App\Models\ContentArticle as ContentArticleEloquentModel;
use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentContentArticleRepository extends EloquentRepository implements ContentArticleRepository
{
    public function __construct(ContentArticleEloquentModel $model)
    {
        $this->model = $model;
    }
    
    public function save(ContentArticle $article): void
    {
        $data = $article->toPrimitives();

        $id = $data['id'] ?? null;
        unset($data['id']);
        $model = $id ? $this->model->find($id) : null;

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

    public function search(int $id): ?ContentArticle
    {
        $model = $this->model->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findBySlug(string $slug, string $locale): ?ContentArticle
    {
        // JSON query to find slug in specific locale
        // "slug" column is JSON: {"es": "slug-es", "en": "slug-en"}
        $model = $this->model->where("slug->$locale", $slug)->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(ContentArticleEloquentModel $model): ContentArticle
    {
        return ContentArticle::fromPrimitives($model->toArray());
    }

    public function remove(int $id): void
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }
}
