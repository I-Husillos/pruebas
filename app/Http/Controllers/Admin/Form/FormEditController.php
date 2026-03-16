<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Inertia\Response;
use Termosalud\Web\Form\Application\Find\FindFormByIdQuery;

final class FormEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        $form = $this->queryBus->ask(new FindFormByIdQuery($id));

        if (!$form) {
            abort(404);
        }

        return $this->render('Admin/Forms/Edit', [
            'form' => $form->toArray(),
        ]);
    }
}
