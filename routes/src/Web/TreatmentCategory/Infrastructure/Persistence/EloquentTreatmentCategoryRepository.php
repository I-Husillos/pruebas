<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Infrastructure\Persistence;

use App\Models\TreatmentCategory as EloquentModel;
use App\Models\TreatmentCategoryTranslation;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class EloquentTreatmentCategoryRepository extends EloquentRepository implements TreatmentCategoryRepository
{
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'         => 'treatment_categories.id',
        'title'      => 'treatment_category_translations.title',
        'slug'       => 'treatment_category_translations.slug',
        'status'     => 'treatment_categories.status',
        'order'      => 'treatment_categories.order',
        'created_at' => 'treatment_categories.created_at',
        'updated_at' => 'treatment_categories.updated_at',
    ];

    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(TreatmentCategory $category): void
    {
        $primitives = $category->toPrimitives();

        $id = $primitives['id'] ?? null;
        unset($primitives['id'], $primitives['created_at'], $primitives['updated_at']);

        $mainData = [
            'status' => $primitives['status'],
            'order'  => $primitives['order'],
        ];

        $model = ($id !== null && $id > 0) ? $this->model->find($id) : null;

        if ($model) {
            $model->update($mainData);
        } else {
            $model = $this->model->create($mainData);
        }

        foreach ($primitives['translations'] as $translation) {
            $model->translations()->updateOrCreate(
                [
                    'treatment_category_id' => $model->id,
                    'language_id'         => $translation['language_id'],
                ],
                [
                    'title'        => $translation['title'],
                    'description'  => $translation['description'] ?? null,
                    'slug'         => $translation['slug'],
                    'seo_metadata' => $translation['seo_metadata'] ?? null,
                ]
            );
        }
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('treatment_category_translations', 'treatment_categories.id', '=', 'treatment_category_translations.treatment_category_id')
            ->select('treatment_categories.*')
            ->distinct()
            ->with('translations');

        $models = $query->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
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
            ->leftJoin('treatment_category_translations', 'treatment_categories.id', '=', 'treatment_category_translations.treatment_category_id')
            ->distinct();

        return (int) $query->count('treatment_categories.id');
    }

    public function search(int $id): ?TreatmentCategory
    {
        $model = $this->model->with('translations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function remove(int $id): void
    {
        $this->model->destroy($id);
    }

    private function toDomain(EloquentModel $model): TreatmentCategory
    {
        $translations = $model->translations->map(fn(TreatmentCategoryTranslation $t) => [
            'language_id'  => $t->language_id,
            'title'        => $t->title,
            'description'  => $t->description,
            'slug'         => $t->slug,
            'seo_metadata' => $t->seo_metadata,
        ])->values()->toArray();

        return TreatmentCategory::fromPrimitives($model->toArray() + ['translations' => $translations]);
    }
}
