<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Termosalud\Web\User\Application\Find\FindUserByIdQuery;

final class UserEditController extends BaseController
{
    public function __construct(private readonly QueryBus $queryBus) {}

    public function __invoke(int $id): Response
    {
        /** @var \Termosalud\Users\Application\UserResponse|null $user */
        $user = $this->queryBus->ask(new FindUserByIdQuery($id));

        if (! $user) {
            abort(404);
        }

        return $this->render('Admin/Users/Edit', [
            'user' => $user->toArray(),
            'roles' => Role::all()->pluck('name'),
        ]);
    }
}
