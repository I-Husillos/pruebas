<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class TreatmentCategoriesIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/TreatmentCategories/Index', [
            'apiUrl' => route('api.v1.treatment-categories.list'),
        ]);
    }
}
