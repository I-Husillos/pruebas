<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Infrastructure\Persistence;

use App\Models\ProductCategory as EloquentModel;
use App\Models\ProductCategoryTranslation;
use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

final class EloquentProductCategoryRepository extends EloquentRepository implements ProductCategoryRepository
{
    // Mapping of domain criteria fields to Eloquent model fields for filtering and sorting
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'         => 'product_categories.id',
        'title'      => 'product_category_translations.title',
        'slug'       => 'product_category_translations.slug',
        'status'     => 'product_categories.status',
        'order'      => 'product_categories.order',
        'created_at' => 'product_categories.created_at',
        'updated_at' => 'product_categories.updated_at',
    ];

    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(ProductCategory $category): void
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
                    'product_category_id' => $model->id,
                    'language_id'         => $translation['language_id'],
                ],
                [
                    'title'         => $translation['title'],
                    'description'  => $translation['description'] ?? null,
                    'slug'         => $translation['slug'],
                    'seo_metadata' => $translation['seo_metadata'] ?? null,
                ]
            );
        }
    }

    public function remove(int $id): void
    {
        $this->model->destroy($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('product_category_translations', 'product_categories.id', '=', 'product_category_translations.product_category_id')
            ->select('product_categories.*')
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
            ->leftJoin('product_category_translations', 'product_categories.id', '=', 'product_category_translations.product_category_id')
            ->distinct();

        return (int) $query->count('product_categories.id');
    }

    public function search(int $id): ?ProductCategory
    {
        $model = $this->model->with('translations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(EloquentModel $model): ProductCategory
    {
        $translations = $model->translations->map(fn(ProductCategoryTranslation $t) => [
            'language_id'  => $t->language_id,
            'title'        => $t->title,
            'description'  => $t->description,
            'slug'         => $t->slug,
            'seo_metadata' => $t->seo_metadata,
        ])->values()->toArray();

        return ProductCategory::fromPrimitives($model->toArray() + ['translations' => $translations]);
    }
}
