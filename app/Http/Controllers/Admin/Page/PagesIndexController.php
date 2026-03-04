<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class PagesIndexController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Pages/Index', [
            'apiUrl' => route('api.v1.pages.list'),
        ]);
    }
}
