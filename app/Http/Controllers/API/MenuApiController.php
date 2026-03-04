<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuApiController extends Controller
{
    public function getItems(int $menuId): JsonResponse
    {
        $menu = Menu::with(['items' => function ($query) {
            $query->where('active', true)
                ->with(['children' => function ($q) {
                    $q->where('active', true)->orderBy('sort_order');
                }])
                ->orderBy('sort_order');
        }])->findOrFail($menuId);

        return response()->json($menu->items);
    }
}
