<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class UsersIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Users/Index', [
            'apiUrl' => route('api.v1.users.list'),
        ]);
    }
}
