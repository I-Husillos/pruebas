<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
use App\Models\Market;
use Inertia\Response;

final class MarketCreateController extends BaseController
{
    public function __invoke(): Response
    {
        $languages = Language::where('active', 1)->get(['id', 'code', 'name']);

        // Obtener regiones distintas de la base de datos y formatearlas para el select
        $regionsRaw = Market::query()->distinct()->pluck('region')->filter()->values();
        $regionOptions = $regionsRaw->map(function ($region) {
            return [
                'code' => $region,
                'name' => str_replace('_', ' ', $region),
            ];
        });

        return $this->render('Admin/Markets/Create', [
            'languages' => $languages,
            'regions' => $regionOptions,
        ]);
    }
}
