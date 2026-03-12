<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Infrastructure\Persistence;

use App\Models\ContentArticle as ContentArticleEloquentModel;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

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
                        'slug'         => $loc['slug'] ?? null,
                        'title'        => $loc['title'] ?? null,
                        'excerpt'      => $loc['excerpt'] ?? null,
                        'description'  => $loc['description'] ?? null,
                        'content'      => $loc['content'] ?? null,
                        'seo_metadata' => $loc['seo_metadata'] ?? [],
                    ]
                );
            }
        });
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
        $query = $this->matching($eloquentCriteria)->with('localizations');
        $models = $query->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $countCriteria = new Criteria($criteria->filters(), $criteria->order(), null, null);
        $eloquentCriteria = EloquentCriteriaConverter::convert($countCriteria);
        $query = $this->matching($eloquentCriteria);

        return $query->count();
    }

    public function search(int $id): ?ContentArticle
    {
        $model = $this->model->newQuery()->with('localizations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findBySlug(string $slug, int $languageId, int $marketId): ?ContentArticle
    {
        $model = $this->model->newQuery()
            ->whereHas('localizations', function ($q) use ($slug, $languageId, $marketId): void {
                $q->where('slug', $slug)
                    ->where('language_id', $languageId)
                    ->where('market_id', $marketId);
            })
            ->with('localizations')
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(ContentArticleEloquentModel $model): ContentArticle
    {
        return ContentArticle::fromPrimitives($model->toArray());
    }

    public function remove(int $id): void
    {
        $model = $this->model->newQuery()->find($id);
        if ($model) {
            $model->forceDelete();
        }
    }
}
