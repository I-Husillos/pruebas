<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WidgetZone;
use Illuminate\Http\JsonResponse;

class WidgetApiController extends Controller
{
    public function getByZone(string $zoneKey): JsonResponse
    {
        $zone = WidgetZone::where('key', $zoneKey)
            ->where('active', true)
            ->with(['widgets' => function ($query) {
                $query->where('active', true)->orderBy('sort_order');
            }])
            ->first();

        if (! $zone) {
            return response()->json([]);
        }

        return response()->json($zone->widgets);
    }
}
