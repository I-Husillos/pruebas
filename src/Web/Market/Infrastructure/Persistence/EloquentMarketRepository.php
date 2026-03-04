<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Infrastructure\Persistence;

use App\Models\Market as EloquentModel;
use Termosalud\Web\Market\Domain\Market;
use Termosalud\Web\Market\Domain\MarketRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Termosalud\Web\Market\Domain\MarketCode;

final class EloquentMarketRepository implements MarketRepository
{
    public function save(Market $market): void
    {
        EloquentModel::updateOrCreate(
            ['code' => $market->code()->value()],
            [
                'name' => $market->name(),
                'region' => $market->region(),
                'default_language' => $market->defaultLanguage(),
                'enabled_languages' => json_encode($market->enabledLanguages()),
                'active' => $market->isActive(),
                'priority' => $market->priority(),
            ]
        );
    }

    public function findByCode(MarketCode $code): ?Market
    {
        $model = EloquentModel::where('code', $code->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAllActive(): array
    {
        $models = EloquentModel::where('active', true)->orderBy('priority', 'desc')->get();

        return collect($models)->map(fn($m) => $this->toDomain($m))->toArray();
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

    private function toDomain(EloquentModel $model): Market
    {
        return new Market(
            isset($model->id) ? (int) $model->id : null,
            new MarketCode($model->code),
            $model->name,
            $model->region,
            $model->default_language,
            is_string($model->enabled_languages) ? json_decode($model->enabled_languages, true) : ($model->enabled_languages ?? []),
            (bool) $model->active,
            (int) $model->priority
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
}
