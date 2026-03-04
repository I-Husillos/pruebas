<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Form\Application\Find\FindFormByKeyQuery;

class FormController extends Controller
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function show(string $market, string $lang, string $key): Response
    {
        // For now, ignoring market/lang or passing them if needed
        // Assuming $key is unique for the form regardless of lang

        /** @var \Termosalud\Forms\Application\FormResponse|null $form */
        $form = $this->queryBus->ask(new FindFormByKeyQuery($key));

        if (! $form) {
            abort(404);
        }

        return Inertia::render('Forms/Show', [
            'market' => $market,
            'lang' => $lang,
            'form' => $form,
        ]);
    }
}
