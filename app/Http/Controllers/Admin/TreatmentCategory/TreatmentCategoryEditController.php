<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Controller;
use App\Models\TreatmentCategory;
use Inertia\Inertia;
use Inertia\Response;

final class TreatmentCategoryEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $category = TreatmentCategory::findOrFail($id);

        return Inertia::render('Admin/TreatmentCategories/Edit', [
            'category' => $category,
        ]);
    }
}
