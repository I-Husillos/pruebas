<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class LanguagesIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Languages/Index', [
            'apiUrl' => route('api.v1.languages.list'),
        ]);
    }
}
