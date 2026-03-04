<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Page\Application\BySlugQuery;

class PageController extends Controller
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function show(string $market, string $lang, string $slug): Response
    {
        $cacheKey = "pages.show.{$market}.{$lang}.{$slug}";

        $page = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($market, $lang, $slug) {
            /** @var \Termosalud\Content\Application\PageResponse|null $response */
            $response = $this->queryBus->ask(new FindPageBySlugQuery($market, $lang, $slug));
            return $response;
        });

        if (! $page || ! $page->isActive) {
            abort(404);
        }

        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        return Inertia::render('Pages/Show', [
            'market' => $market,
            'lang' => $lang,
            'markets' => $markets,
            'languages' => $languages,
            'page' => $page,
            'editUrl' => route('admin.pages.edit', $page->id),
        ]);
    }
}
