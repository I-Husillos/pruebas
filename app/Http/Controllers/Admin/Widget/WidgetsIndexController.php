<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Widget;

use App\Http\Controllers\Controller;
use App\Models\WidgetZone;
use Inertia\Inertia;
use Inertia\Response;

final class WidgetsIndexController extends Controller
{
    public function __invoke(): Response
    {
        $zones = WidgetZone::with('widgets')->orderBy('key')->get();

        return Inertia::render('Admin/Widgets/Index', [
            'zones' => $zones,
        ]);
    }
}
