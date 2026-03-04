<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Treatment;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use App\Models\TreatmentCategory;
use App\Models\Market;

final class TreatmentCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Treatments/Create', [
            'forms' => DB::table('forms')->select('id', 'name', 'key')->get(),
            'categories' => TreatmentCategory::orderBy('sort_order')->get(),
            'markets' => Market::where('active', true)->get(),
        ]);
    }
}
