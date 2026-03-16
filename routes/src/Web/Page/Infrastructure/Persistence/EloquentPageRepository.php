<?php

namespace Termosalud\Web\Page\Infrastructure\Persistence;

use App\Models\Page as EloquentModel;
use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;

class EloquentPageRepository implements PageRepository
{
    private EloquentModel $model;

    public function __construct(EloquentModel $model)
    {
        $this->model = $model;
    }
    public function save(Page $page): Page
    {
        // Initialize blocks_json with current language key
        $blocks = [$page->languageCode => $page->blocks];

        $data = [
            'market_code' => $page->marketCode,
            'language_code' => $page->languageCode,
            'slug' => $page->slug,
            'is_active' => $page->isActive,
            'seo_title' => $page->seoTitle,
            'seo_description' => $page->seoDescription,
            'blocks_json' => $blocks,
        ];

        $model = EloquentModel::create($data);

        return $this->toDomain($model);
    }

    public function findBySlug(string $market, string $lang, string $slug): ?Page
    {
        $model = EloquentModel::where('market_code', $market)
            ->where('language_code', $lang)
            ->where('slug', $slug)
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function search(int $id): ?Page
    {
        $model = EloquentModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function update(Page $page): void
    {
        $model = EloquentModel::find($page->id);
        if ($model) {
            $currentBlocks = $model->blocks_json ?? [];
            // If it's a flat array (from old format or error), convert to localized
            if (is_array($currentBlocks) && !isset($currentBlocks[$page->languageCode]) && !empty($currentBlocks) && array_is_list($currentBlocks)) {
                $currentBlocks = [$model->language_code => $currentBlocks];
            }

            // Set/Update current language blocks
            $currentBlocks[$page->languageCode] = $page->blocks;

            $model->update([
                'market_code' => $page->marketCode,
                'language_code' => $page->languageCode,
                'slug' => $page->slug,
                'is_active' => $page->isActive,
                'seo_title' => $page->seoTitle,
                'seo_description' => $page->seoDescription,
                'blocks_json' => $currentBlocks,
            ]);
        }
    }

    public function remove(int $id): void
    {
        EloquentModel::destroy($id);
    }

    public function delete(int $id): void
    {
        $this->remove($id);
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

    private function toDomain(EloquentModel $model): Page
    {
        $blocks = $model->blocks_json ?? [];

        // Extract localized blocks if exist
        if (isset($blocks[$model->language_code])) {
            $blocks = $blocks[$model->language_code];
        }

        return new Page(
            $model->id,
            $model->market_code,
            $model->language_code,
            $model->slug,
            (bool) $model->is_active,
            $model->seo_title,
            $model->seo_description,
            $blocks
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
