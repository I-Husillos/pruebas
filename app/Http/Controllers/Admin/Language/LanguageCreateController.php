<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class LanguageCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Languages/Create');
    }
}
