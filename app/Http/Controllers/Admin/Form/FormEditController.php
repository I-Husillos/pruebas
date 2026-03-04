<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\Form\Application\Find\FindFormByIdQuery;

final class FormEditController extends Controller
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Forms\Application\FormResponse|null $form */
        $form = $this->queryBus->ask(new FindFormByIdQuery($id));

        if (! $form) {
            abort(404);
        }

        return Inertia::render('Admin/Forms/Edit', [
            'form' => $form,
        ]);
    }
}
