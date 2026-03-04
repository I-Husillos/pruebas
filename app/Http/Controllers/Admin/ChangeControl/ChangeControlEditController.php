<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ChangeControl;

use App\Http\Controllers\Controller;
use App\Models\ChangeControl;
use Inertia\Inertia;
use Inertia\Response;

final class ChangeControlEditController extends Controller
{
    public function __invoke(string $id): Response
    {
        $changeControl = ChangeControl::with(['requester', 'changeable'])->findOrFail($id);
        $this->authorize('view', $changeControl);

        return Inertia::render('Admin/ChangeControls/Edit', [
            'changeControl' => $changeControl,
        ]);
    }
}
