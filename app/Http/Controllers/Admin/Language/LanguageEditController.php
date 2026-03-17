<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
use Inertia\Inertia;
use Inertia\Response;

final class LanguageEditController extends BaseController
{
    public function __invoke(int $id): Response
    {
        $language = Language::findOrFail($id);

        return $this->render('Admin/Languages/Edit', [
            'language' => $language,
        ]);
    }
}
