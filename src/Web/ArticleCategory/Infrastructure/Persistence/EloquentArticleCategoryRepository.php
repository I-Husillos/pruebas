<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Infrastructure\Persistence;

use App\Models\ArticleCategory as EloquentModel;
use App\Models\ArticleCategoryTranslation;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategory;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentArticleCategoryRepository extends EloquentRepository implements ArticleCategoryRepository
{
    // Mapping of domain criteria fields to Eloquent model fields for filtering and sorting
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'         => 'article_categories.id',
        'title'      => 'article_category_translations.title',
        'slug'       => 'article_category_translations.slug',
        'status'     => 'article_categories.status',
        'order'      => 'article_categories.order',
    ];

    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(ArticleCategory $category): void
    {
        $primitives = $category->toPrimitives();

        $id = $primitives['id'] ?? null;

        $mainData = [
            'status' => $primitives['status'],
            'order'  => $primitives['order'],
        ];

        /** @var EloquentModel|null $model */
        $model = ($id !== null && $id > 0) ? $this->model->find($id) : null;

        if ($model) {
            $model->update($mainData);
        } else {
            $model = $this->model->create($mainData);
        }

        foreach ($primitives['translations'] as $translation) {
            $model->translations()->updateOrCreate(
                [
                    'article_category_id' => $model->id,
                    'language_id' => $translation['language_id'],
                ],
                [
                    'title' => $translation['title'],
                    'description'  => $translation['description'] ?? null,
                    'slug' => $translation['slug'],
                    'seo_metadata' => $translation['seo_metadata'] ?? null,
                ]
            );
        }
    }

    public function remove(int $id): void
    {
        $this->model->destroy($id);
    }


    public function findBySlug(string $slug, int $languageId): ?ArticleCategory
    {
        $model = $this->model
            ->whereHas('translations', function ($query) use ($slug, $languageId) {
                $query->where('slug', $slug)
                    ->where('language_id', $languageId);
            })
            ->with('translations')
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    
    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('article_category_translations', 'article_categories.id', '=', 'article_category_translations.article_category_id')
            ->select('article_categories.*')
            ->distinct()
            ->with('translations');

        $models = $query->get();

        return collect($models)->map(fn($model) => $this->toDomain($model))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $countCriteria = new Criteria(
            $criteria->filters(),
            $criteria->order(),
            null,
            null
        );

        $eloquentCriteria = EloquentCriteriaConverter::convert($countCriteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('article_category_translations', 'article_categories.id', '=', 'article_category_translations.article_category_id')
            ->distinct();

        return (int) $query->count('article_categories.id');
    }

    public function search(int $id): ?ArticleCategory
    {
        $model = $this->model->with('translations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(EloquentModel $model): ArticleCategory
    {
        $translations = $model->translations->map(fn(ArticleCategoryTranslation $t) => [
            'language_id' => $t->language_id,
            'title' => $t->title,
            'description' => $t->description,
            'slug' => $t->slug,
            'seo_metadata' => $t->seo_metadata,
        ])->values()->toArray();

        return ArticleCategory::fromPrimitives($model->toArray() + ['translations' => $translations]);
    }
}
