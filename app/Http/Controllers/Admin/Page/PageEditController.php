<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Page\Application\Find\FindPageByIdQuery;

final class PageEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Content\Application\PageResponse|null $page */
        $page = $this->queryBus->ask(new FindPageByIdQuery($id));

        if (! $page) {
            abort(404);
        }

        return Inertia::render('Admin/Pages/Edit', [
            'page' => $page->toArray(),
            'markets'    => \App\Models\Market::where('active', true)->get(['id', 'code', 'name']),
            'languages'  => \App\Models\Language::where('active', true)->get(['id', 'code', 'name']),
            'forms'      => \App\Models\Form::all(['id', 'name']),
        ]);
    }
}
