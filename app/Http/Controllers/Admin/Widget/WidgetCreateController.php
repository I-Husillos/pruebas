<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\WidgetZone;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class WidgetCreateController extends Controller
{
    public function __invoke(): Response
    {
        $zones = WidgetZone::where('active', true)->orderBy('key')->get();
        $menus = Menu::where('active', true)->get();
        $forms = DB::table('forms')->where('active', true)->get();

        return Inertia::render('Admin/Widgets/Create', [
            'zones' => $zones,
            'menus' => $menus,
            'forms' => $forms,
        ]);
    }
}
