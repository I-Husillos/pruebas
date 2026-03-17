<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class TreatmentCategoryCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/TreatmentCategories/Create');
    }
}
