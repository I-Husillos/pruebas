<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Infrastructure\Persistence;

use App\Models\Page as PageEloquentModel;
use App\Models\PageLocalization;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;

final class EloquentPageRepository extends EloquentRepository implements PageRepository
{
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'         => 'pages.id',
        'status'     => 'pages.status',
        'slug'       => 'page_localizations.slug',
        'title'      => 'page_localizations.title',
        'market_id'  => 'page_localizations.market_id',
        'language_id'=> 'page_localizations.language_id',
        'created_at' => 'pages.created_at',
        'updated_at' => 'pages.updated_at',
    ];

    public function __construct(PageEloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(Page $page): void
    {
        $data          = $page->toPrimitives();
        $id            = $data['id'] ?? null;
        $localizations = $data['localizations'] ?? [];

        DB::transaction(function () use ($id, $data, $localizations): void {
            $model = $id ? $this->model->newQuery()->find($id) : null;

            if ($model) {
                $model->update(['status' => $data['status']]);
            } else {
                $model = $this->model->newQuery()->create(['status' => $data['status']]);
            }

            foreach ($localizations as $loc) {
                $model->localizations()->updateOrCreate(
                    [
                        'language_id' => (int) $loc['language_id'],
                        'market_id'   => (int) $loc['market_id'],
                    ],
                    [
                        'slug'         => $loc['slug']         ?? null,
                        'title'        => $loc['title']        ?? null,
                        'excerpt'      => $loc['excerpt']      ?? null,
                        'description'  => $loc['description']  ?? null,
                        'content'      => $loc['content']      ?? null,
                        'seo_metadata' => $loc['seo_metadata'] ?? [],
                    ]
                );
            }
        });
    }

    public function search(int $id): ?Page
    {
        $model = $this->model->newQuery()->with('localizations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findBySlug(string $slug, int $languageId, int $marketId): ?Page
    {
        $model = $this->model->newQuery()
            ->whereHas('localizations', function ($q) use ($slug, $languageId, $marketId): void {
                $q->where('slug', $slug)
                ->where('language_id', $languageId)
                ->where('market_id', $marketId);
            })
            ->with(['localizations' => function ($q) use ($languageId, $marketId): void {
                $q->where('language_id', $languageId)
                ->where('market_id', $marketId);
            }])
            ->first();

        return $model ? $this->toDomain($model) : null;
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
        $loc = PageLocalization::query()->find($localizationId);
        if ($loc) {
            $loc->delete();
        }
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eq = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eq)
            ->leftJoin('page_localizations', 'pages.id', '=', 'page_localizations.page_id')
            ->select('pages.*')
            ->distinct()
            ->with('localizations');

        return collect($query->get())->map(fn ($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $countCriteria = new Criteria($criteria->filters(), $criteria->order(), null, null);
        $eq = EloquentCriteriaConverter::convert($countCriteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eq)
            ->leftJoin('page_localizations', 'pages.id', '=', 'page_localizations.page_id')
            ->select('pages.*')
            ->distinct();

        return $query->count();
    }

    private function toDomain(PageEloquentModel $model): Page
    {
        return Page::fromPrimitives($model->toArray());
    }
}
