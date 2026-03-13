<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\BaseController;
use App\Models\ArticleCategoryTranslation;
use App\Models\Language;
use App\Models\Market;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Inertia;
use Inertia\Response;
use Termosalud\Web\Article\Application\Find\FindArticleByIdQuery;

final class ArticleEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Web\Article\Application\ArticleResponse|null $article */
        $article = $this->queryBus->ask(new FindArticleByIdQuery($id));

        if (!$article) {
            abort(404);
        }

        // Reutilizamos exactamente la misma lógica de ArticleCreateController
        // para construir el array de mercados con sus idiomas.
        $languages = Language::where('active', 1)->get(['id', 'code', 'name', 'native_name'])->keyBy('code');

        $markets = Market::query()
            ->where('active', true)
            ->orderBy('priority')
            ->get()
            ->map(function (Market $market) use ($languages) {
                $enabledLanguages = collect($market->enabled_languages ?? [])
                    ->map(function (string $code) use ($languages, $market) {
                        $language = $languages->get($code);
                        if (!$language) return null;
                        return [
                            'id'         => $language->id,
                            'code'       => $language->code,
                            'name'       => $language->native_name ?: $language->name,
                            'is_default' => $language->code === $market->default_language,
                        ];
                    })
                    ->filter()
                    ->sortByDesc(fn(array $l) => $l['is_default'])
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
            ->filter(fn(array $m) => !empty($m['languages']))
            ->values();

        return Inertia::render('Admin/Articles/Edit', [
            'categories' => ArticleCategoryTranslation::all(),
            'article'    => $article->toArray(),
            // markets es necesario para que LocalizationTabs sepa
            // qué pestañas mostrar y a qué IDs corresponden
            'markets'    => $markets,
            'categories' => \App\Models\ArticleCategory::all(['id', 'status']),
        ]);
    }
}
