<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

final class FormCreateController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Forms/Create');
    }
}
