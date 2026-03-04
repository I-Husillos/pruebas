<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class TreatmentCategoryCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/TreatmentCategories/Create');
    }
}
