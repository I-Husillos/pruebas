<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Form;
use App\Models\Language;
use App\Models\Market;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Response;
use Termosalud\Web\Page\Application\Find\FindPageByIdQuery;

final class PageEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Web\Page\Application\PageResponse|null $page */
        $page = $this->queryBus->ask(new FindPageByIdQuery($id));

        if (! $page) {
            abort(404);
        }

        $languages = Language::where('active', 1)
            ->get(['id', 'code', 'name', 'native_name'])
            ->keyBy('code');

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

        return $this->render('Admin/Pages/Edit', [
            'page'    => $page->toArray(),
            'markets' => $markets,
            'forms'   => $forms,
        ]);
    }
}
