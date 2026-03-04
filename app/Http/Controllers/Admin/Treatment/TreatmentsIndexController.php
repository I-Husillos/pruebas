<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Treatment;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class TreatmentsIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Treatments/Index', [
            'apiUrl' => route('api.v1.treatments.list'),
        ]);
    }
}
