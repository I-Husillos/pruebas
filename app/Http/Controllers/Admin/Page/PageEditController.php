<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Page\Application\ByIdQuery;

final class PageEditController extends Controller
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
            'page' => $page,
            'markets' => DB::table('markets')->get(),
            'languages' => DB::table('languages')->get(),
            'forms' => DB::table('forms')->select('id', 'name', 'key')->get(),
        ]);
    }
}
