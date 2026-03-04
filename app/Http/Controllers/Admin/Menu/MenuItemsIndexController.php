<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Inertia\Inertia;
use Inertia\Response;

final class MenuItemsIndexController extends Controller
{
    public function __invoke(int $menuId): Response
    {
        $menu = Menu::with(['items' => function ($query) {
            $query->with('children');
        }])->findOrFail($menuId);

        return Inertia::render('Admin/MenuItems/Index', [
            'menu' => $menu,
            'items' => $menu->items,
        ]);
    }
}
