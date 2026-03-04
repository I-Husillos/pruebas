<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Inertia\Inertia;
use Inertia\Response;

final class MenuItemCreateController extends Controller
{
    public function __invoke(int $menuId): Response
    {
        $menu = Menu::findOrFail($menuId);
        $parentItems = MenuItem::where('menu_id', $menuId)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/MenuItems/Create', [
            'menu' => $menu,
            'parentItems' => $parentItems,
        ]);
    }
}
