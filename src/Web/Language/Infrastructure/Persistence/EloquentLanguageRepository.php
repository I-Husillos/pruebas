<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Infrastructure\Persistence;

use App\Models\Language as EloquentModel;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Termosalud\Web\Language\Domain\Language;
use Termosalud\Web\Language\Domain\LanguageRepository;

final class EloquentLanguageRepository implements LanguageRepository
{
    public function save(Language $language): void
    {
        $data = $language->toPrimitives();
        $model = EloquentModel::where('code', $data['code'])->first();

        if (! $model) {
            $model = new EloquentModel();
            $model->code = $data['code'];
        }

        $model->name = $data['name'];
        $model->native_name = $data['native_name'];
        $model->direction = $data['direction'];
        $model->active = $data['active'];
        $model->fallback_language = $data['fallback_language'];

        $model->save();
    }

    public function search(int $id): ?Language
    {
        $model = EloquentModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function remove(int $id): void
    {
        $model = EloquentModel::find($id);
        if ($model) {
            $model->forceDelete();
        }
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
    public function searchByCriteria(Criteria $criteria): array
    {
        $query = EloquentModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $query = EloquentModel::query();
        
        // Create criteria without pagination for counting
        $countCriteria = new Criteria(
            $criteria->filters(),
            $criteria->order(),
            null,
            null
        );
        
        $this->applyCriteria($query, $countCriteria);

        return $query->count();
    }

    private function applyCriteria($query, Criteria $criteria): void
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria);
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
        return Language::fromPrimitives($model->toArray());
    }
}
