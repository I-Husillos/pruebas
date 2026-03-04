<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Inertia\Inertia;
use Inertia\Response;

final class LanguageEditController extends Controller
{
    public function __invoke(int $id): Response
    {
        $language = Language::findOrFail($id);

        return Inertia::render('Admin/Languages/Edit', [
            'language' => $language,
        ]);
    }
}
