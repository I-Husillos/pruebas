<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Infrastructure\Persistence;

use App\Models\Product as ProductEloquentModel;
use App\Models\ProductLocalization;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductRepository;
use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;
use Dba\DddSkeleton\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use Illuminate\Support\Facades\DB;

final class EloquentProductRepository extends EloquentRepository implements ProductRepository
{
    private const CRITERIA_TO_ELOQUENT_FIELDS = [
        'id'                  => 'products.id',
        'product_category_id' => 'products.product_category_id',
        'code'                => 'products.code',
        'status'              => 'products.status',
        'title'               => 'product_localizations.title',
        'slug'                => 'product_localizations.slug',
        'order'               => 'products.order',
        'created_at'          => 'products.created_at',
        'updated_at'          => 'products.updated_at',
    ];

    public function __construct(ProductEloquentModel $model)
    {
        $this->model = $model;
    }

    public function save(Product $product): void
    {
        $data = $product->toPrimitives();
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

    public function search(int $id): ?Product
    {
        $model = $this->model->newQuery()->with('localizations')->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $eloquentCriteria = EloquentCriteriaConverter::convert($criteria, self::CRITERIA_TO_ELOQUENT_FIELDS);
        $query = $this->matching($eloquentCriteria)
            ->leftJoin('product_localizations', 'products.id', '=', 'product_localizations.product_id')
            ->select('products.*')
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
            ->leftJoin('product_localizations', 'products.id', '=', 'product_localizations.product_id')
            ->select('products.*')
            ->distinct();

        return $query->count();
    }

    private function toDomain(ProductEloquentModel $model): Product
    {
        return Product::fromPrimitives($model->toArray());
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
        $model = ProductLocalization::query()->find($localizationId);
        if ($model) {
            $model->delete();
        }
    }
}
