<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;
use Spatie\Permission\Models\Role;

final class UserCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Users/Create', [
            'roles' => Role::all()->pluck('name'),
        ]);
    }
}
