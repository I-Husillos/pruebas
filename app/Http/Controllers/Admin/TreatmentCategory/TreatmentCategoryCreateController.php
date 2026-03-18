<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TreatmentCategory;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
use Inertia\Response;

final class TreatmentCategoryCreateController extends BaseController
{
    public function __invoke(): Response
    {
        $languages = Language::where('active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'native_name'])
            ->map(fn($l) => [
                'id'   => $l->id,
                'code' => $l->code,
                'name' => $l->native_name ?: $l->name,
            ])
            ->values();

        return $this->render('Admin/TreatmentCategories/Create', [
            'languages' => $languages,
        ]);
    }
}
