<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class MenusIndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $search = $request->get('search');

        $query = Menu::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.es') LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                    ->orWhere('key', 'like', "%{$search}%");
            });
        }

        $menus = $query->withCount('allItems')
            ->with(['items' => function ($query) {
                $query->whereNull('parent_id')
                    ->where('active', true)
                    ->with(['children' => function ($q) {
                        $q->where('active', true)->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order');
            }])
            ->paginate(15);

        return Inertia::render('Admin/Menus/Index', [
            'menus' => $menus,
            'search' => $search,
        ]);
    }
}
