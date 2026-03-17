<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class FormCreateController extends BaseController
{
    public function __invoke(): Response
    {
        return $this->render('Admin/Forms/Create');
    }
}
