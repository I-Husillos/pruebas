<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class MenuCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Menus/Create');
    }
}
