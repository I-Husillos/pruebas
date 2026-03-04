<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Widget;
use App\Models\WidgetZone;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class WidgetEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $widget = Widget::with('zone')->findOrFail($id);
        $zones = WidgetZone::where('active', true)->orderBy('key')->get();
        $menus = Menu::where('active', true)->get();
        $forms = DB::table('forms')->where('active', true)->get();

        return Inertia::render('Admin/Widgets/Edit', [
            'widget' => $widget,
            'zones' => $zones,
            'menus' => $menus,
            'forms' => $forms,
        ]);
    }
}
