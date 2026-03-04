<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Infrastructure\Persistence;

use App\Models\ContentArticle as EloquentModel;
use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentContentArticleRepository implements ContentArticleRepository
{
    public function save(ContentArticle $article): void
    {
        EloquentModel::updateOrCreate(
            ['id' => $article->id()],
            [
                'type' => $article->type(),
                'title' => $article->title(),
                'slug' => $article->slug(),
                'excerpt' => $article->excerpt(),
                'content' => $article->content(),
                'author' => $article->author(),
                'published' => $article->published(),
                'published_at' => $article->publishedAt()?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function search(array $criteria): array
    {
        // Simple implementation
        $query = EloquentModel::query();

        if (isset($criteria['type'])) {
            $query->where('type', $criteria['type']);
        }

        return $query->get()->map(fn($model) => $this->toDomain($model))->toArray();
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

    public function findById(int $id): ?ContentArticle
    {
        $model = EloquentModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findBySlug(string $slug, string $locale): ?ContentArticle
    {
        // JSON query to find slug in specific locale
        // "slug" column is JSON: {"es": "slug-es", "en": "slug-en"}
        $model = EloquentModel::where("slug->$locale", $slug)->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(EloquentModel $model): ContentArticle
    {
        // Hydrate domain object from Eloquent model
        // Using reflection or a protected constructor via a static factory
        // For simplicity here using Reflection or assuming public constructor/factory is capable
        return new ContentArticle(
            $model->id,
            $model->type,
            $model->title,
            $model->slug,
            $model->excerpt,
            $model->content,
            $model->author,
            $model->published,
            $model->published_at ? \DateTimeImmutable::createFromMutable($model->published_at) : null
        );
    }

    private function matching($criteria)
    {
        $criteria = is_array($criteria) === false ? [$criteria] : $criteria;

        return array_reduce($criteria, static function ($query, $criteria) {
            $criteria->each(static function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });

            return $query;
        }, EloquentModel::query());
    }

    public function remove(int $id): void
    {
        EloquentModel::destroy($id);
    }
}
