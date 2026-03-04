<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Inertia\Inertia;
use Inertia\Response;

final class MarketEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $market = Market::findOrFail($id);

        return Inertia::render('Admin/Markets/Edit', [
            'market' => $market,
        ]);
    }
}
