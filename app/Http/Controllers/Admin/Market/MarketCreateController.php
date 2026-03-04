<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class MarketCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Markets/Create');
    }
}
