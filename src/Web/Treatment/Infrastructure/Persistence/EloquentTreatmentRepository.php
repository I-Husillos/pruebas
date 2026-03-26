<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Infrastructure\Persistence;

use App\Models\Treatment as TreatmentEloquentModel;
use App\Models\TreatmentLocalization;
use Termosalud\Web\Treatment\Domain\Treatment;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Illuminate\Support\Facades\DB;

final class EloquentTreatmentRepository extends EloquentRepository implements TreatmentRepository
{
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'                    => 'treatments.id',
        'treatment_category_id' => 'treatments.treatment_category_id',
        'status'                => 'treatments.status',
        'title'                 => 'treatment_localizations.title',
        'slug'                  => 'treatment_localizations.slug',
        'order'                 => 'treatments.order',
        'created_at'            => 'treatments.created_at',
        'updated_at'            => 'treatments.updated_at',
    ];

    public function __construct(TreatmentEloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(Treatment $treatment): void
    {
        $data          = $treatment->toPrimitives();
        $id            = $data['id'] ?? null;
        $localizations = $data['localizations'] ?? [];

        unset($data['id'], $data['localizations'], $data['created_at'], $data['updated_at'], $data['deleted_at']);

        DB::transaction(function () use ($id, $data, $localizations): void {
            $model = $id ? $this->model->newQuery()->find($id) : null;

            if ($model) {
                $model->update($data);
            } else {
                $model = $this->model->newQuery()->create($data);
            }

            foreach ($localizations as $loc) {
                $model->localizations()->updateOrCreate(
                    [
                        'language_id' => (int) $loc['language_id'],
                        'market_id'   => (int) $loc['market_id'],
                    ],
                    [
                        'slug'             => $loc['slug'] ?? null,
                        'title'            => $loc['title'] ?? null,
                        'excerpt'          => $loc['excerpt'] ?? null,
                        'description'      => $loc['description'] ?? null,
                        'content'          => $loc['content'] ?? null,
                        'indications'      => $loc['indications'] ?? null,
                        'contraindications' => $loc['contraindications'] ?? null,
                        'seo_metadata'     => $loc['seo_metadata'] ?? [],
                    ]
                );
            }
        });
    }

    public function search(int $id): ?Treatment
    {
        $model = $this->model->newQuery()->with('localizations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findBySlug(string $slug, int $languageId, int $marketId): ?Treatment
    {
        $model = $this->model->newQuery()
            ->whereHas('localizations', function ($query) use ($slug, $languageId, $marketId) {
                $query->where('slug', $slug)
                    ->where('language_id', $languageId)
                    ->where('market_id', $marketId);
            })
            ->with(['localizations' => function ($query) use ($languageId, $marketId) {
                $query->where('language_id', $languageId)
                    ->where('market_id', $marketId);
            }])
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('treatment_localizations', 'treatments.id', '=', 'treatment_localizations.treatment_id')
            ->select('treatments.*')
            ->distinct()
            ->with('localizations');

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
            ->leftJoin('treatment_localizations', 'treatments.id', '=', 'treatment_localizations.treatment_id')
            ->select('treatments.*')
            ->distinct();

        return $query->count();
    }

    private function toDomain(TreatmentEloquentModel $model): Treatment
    {
        return Treatment::fromPrimitives($model->toArray());
    }

    public function remove(int $id): void
    {
        $model = $this->model->newQuery()->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }

    public function removeLocalization(int $localizationId): void
    {
        $model = TreatmentLocalization::query()->find($localizationId);
        if ($model) {
            $model->delete();
        }
    }
}
