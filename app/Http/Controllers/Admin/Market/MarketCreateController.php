<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Inertia\Inertia;
use Inertia\Response;

final class MarketCreateController extends Controller
{
    public function __invoke(): Response
    {
        $languages = Language::where('active', 1)->get(['id', 'code', 'name']);

        return Inertia::render('Admin/Markets/Create', [
            'languages' => $languages,
        ]);
    }
}
