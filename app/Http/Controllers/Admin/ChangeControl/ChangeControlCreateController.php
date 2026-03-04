<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ChangeControl;

use App\Http\Controllers\Controller;
use App\Models\ChangeControl;
use Inertia\Inertia;
use Inertia\Response;

final class ChangeControlCreateController extends Controller
{
    public function __invoke(): Response
    {
        $this->authorize('create', ChangeControl::class);

        return Inertia::render('Admin/ChangeControls/Create');
    }
}
