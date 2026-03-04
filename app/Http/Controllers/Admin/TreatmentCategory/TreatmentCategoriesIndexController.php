<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Controller;
use App\Models\TreatmentCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class TreatmentCategoriesIndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $search = $request->get('search');

        $query = TreatmentCategory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.es') LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"]);
            });
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(15);

        return Inertia::render('Admin/TreatmentCategories/Index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }
}
