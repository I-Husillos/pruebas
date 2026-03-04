<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Inertia\Inertia;
use Inertia\Response;

final class MenuItemEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $item = MenuItem::with('menu')->findOrFail($id);
        $parentItems = MenuItem::where('menu_id', $item->menu_id)
            ->whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/MenuItems/Edit', [
            'item' => $item,
            'menu' => $item->menu,
            'parentItems' => $parentItems,
        ]);
    }
}
