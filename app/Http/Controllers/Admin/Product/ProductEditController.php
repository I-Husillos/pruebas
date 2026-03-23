<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Form;
use Inertia\Response;
use App\Models\Language;
use App\Models\Market;
use App\Models\ProductCategory;
use Termosalud\Web\Product\Application\Find\FindProductQuery;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;

final class ProductEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Web\Product\Application\ProductResponse|null $product */
        $product = $this->queryBus->ask(new FindProductQuery($id));

        if (! $product) {
            abort(404);
        }

        $categories = ProductCategory::query()
            ->with('translations')
            ->orderBy('id')
            ->get()
            ->map(fn (ProductCategory $category) => [
                'id'    => $category->id,
                'title' => $category->translations->first()?->title
                        ?? "Categoría #{$category->id}",
            ]);

        $languages = Language::where('active', 1)->get(['id', 'code', 'name', 'native_name'])->keyBy('code');

        $markets = Market::query()
            ->where('active', true)
            ->orderBy('priority')
            ->get()
            ->map(function (Market $market) use ($languages) {
                $enabledLanguages = collect($market->enabled_languages ?? [])
                    ->map(function (string $code) use ($languages, $market) {
                        $language = $languages->get($code);
                        if (! $language) return null;
                        return [
                            'id'         => $language->id,
                            'code'       => $language->code,
                            'name'       => $language->native_name ?: $language->name,
                            'is_default' => $language->code === $market->default_language,
                        ];
                    })
                    ->filter()
                    ->sortByDesc(fn (array $l) => $l['is_default'])
                    ->values()
                    ->all();

                return [
                    'id'        => $market->id,
                    'code'      => $market->code,
                    'name'      => $market->name,
                    'region'    => $market->region,
                    'languages' => $enabledLanguages,
                ];
            })
            ->filter(fn (array $m) => ! empty($m['languages']))
            ->values();

        $forms = Form::query()
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'key']);

        return $this->render('Admin/Products/Edit', [
            'product' => $product->toArray(),
            'categories' => $categories,
            'markets'    => $markets,
            'forms'      => $forms,
        ]);
    }
}
