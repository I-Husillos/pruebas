<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Infrastructure\Persistence;

use App\Models\Language as EloquentModel;
use Termosalud\Web\Language\Domain\Language;
use Termosalud\Web\Language\Domain\LanguageRepository;

final class EloquentLanguageRepository implements LanguageRepository
{
    public function save(Language $language): void
    {
        EloquentModel::updateOrCreate(
            ['code' => $language->code()],
            [
                'name' => $language->name(),
                'native_name' => $language->nativeName(),
                'direction' => $language->direction(),
                'active' => $language->isActive(),
                'fallback_language' => $language->fallbackLanguage(),
            ]
        );
    }

    public function findByCode(string $code): ?Language
    {
        $model = EloquentModel::where('code', $code)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findAllActive(): array
    {
        $models = EloquentModel::where('active', true)->get();

        return $models->map(fn($m) => $this->toDomain($m))->toArray();
    }
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array
    {
        $query = EloquentModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int
    {
        $query = EloquentModel::query();
        
        // Create criteria without pagination for counting
        $countCriteria = new \Dba\DddSkeleton\Shared\Domain\Criteria\Criteria(
            $criteria->filters(),
            $criteria->order(),
            null,
            null
        );
        
        $this->applyCriteria($query, $countCriteria);

        return $query->count();
    }

    private function applyCriteria($query, \Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): void
    {
        $eloquentCriteria = \Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter::convert($criteria);
        $criteriaList = is_array($eloquentCriteria) === false ? [$eloquentCriteria] : $eloquentCriteria;

        array_reduce($criteriaList, static function ($query, $criteria) {
            $criteria->each(static function ($method) use ($query) {
                call_user_func_array([$query, $method->name], $method->parameters);
            });
            return $query;
        }, $query);
    }
    private function toDomain(EloquentModel $model): Language
    {
        return new Language(
            isset($model->id) ? (int) $model->id : null,
            $model->code,
            $model->name,
            $model->native_name,
            $model->direction,
            $model->active,
            $model->fallback_language
        );
    }
}
