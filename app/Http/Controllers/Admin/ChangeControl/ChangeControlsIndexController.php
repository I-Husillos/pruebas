<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ChangeControl;

use App\Http\Controllers\Controller;
use App\Models\ChangeControl;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class ChangeControlsIndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $this->authorize('viewAny', ChangeControl::class);

        $changeControls = ChangeControl::query()
            ->with(['requester', 'changeable'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/ChangeControls/Index', [
            'changeControls' => $changeControls,
            'filters' => $request->only(['search']),
        ]);
    }
}
