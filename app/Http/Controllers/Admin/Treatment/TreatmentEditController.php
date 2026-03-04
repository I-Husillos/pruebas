<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Treatment;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\TreatmentCategory;
use App\Models\Market;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

final class TreatmentEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $treatment = Treatment::findOrFail($id);

        return Inertia::render('Admin/Treatments/Edit', [
            'treatment' => $treatment,
            'forms' => DB::table('forms')->select('id', 'name', 'key')->get(),
            'categories' => TreatmentCategory::orderBy('sort_order')->get(),
            'markets' => Market::where('active', true)->get(),
        ]);
    }
}
