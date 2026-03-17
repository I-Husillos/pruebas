<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class LanguageCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Languages/Create');
    }
}
