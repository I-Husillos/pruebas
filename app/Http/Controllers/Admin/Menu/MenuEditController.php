<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Inertia\Inertia;
use Inertia\Response;

final class MenuEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $menu = Menu::with('items.children')->findOrFail($id);

        return Inertia::render('Admin/Menus/Edit', [
            'menu' => $menu,
        ]);
    }
}
