<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use App\Models\ArticleCategory;
use App\Models\Language;
use App\Models\Market;
use Inertia\Inertia;
use Inertia\Response;

final class ArticleCreateController extends BaseController
{
    public function __invoke(): Response
    {
        $categories = ArticleCategory::query()
            ->with('translations')
            ->orderBy('id')
            ->get()
            ->map(fn (ArticleCategory $category) => [
                'id'    => $category->id,
                'title' => $category->translations->first()?->title
                        ?? "Categoría #{$category->id}",
            ]);

        $languages = Language::where('active', 1)->get(['id', 'code', 'name'])->keyBy('code');

        $markets = Market::query()
            ->where('active', true)
            ->orderBy('priority')
            ->get()
            ->map(function (Market $market) use ($languages) {
                $enabledLanguages = collect($market->enabled_languages ?? [])
                    ->map(function (string $code) use ($languages, $market) {
                        $language = $languages->get($code);

                        if (! $language) {
                            return null;
                        }

                        return [
                            'id' => $language->id,
                            'code' => $language->code,
                            'name' => $language->native_name ?: $language->name,
                            'is_default' => $language->code === $market->default_language,
                        ];
                    })
                    ->filter()
                    ->sortByDesc(fn (array $language) => $language['is_default'])
                    ->values()
                    ->all();

                return [
                    'id' => $market->id,
                    'code' => $market->code,
                    'name' => $market->name,
                    'region' => $market->region,
                    'languages' => $enabledLanguages,
                ];
            })
            ->filter(fn (array $market) => ! empty($market['languages']))
            ->values();

        return $this->render('Admin/Articles/Create', [
            'categories' => $categories,
            'markets' => $markets,
        ]);
    }
}