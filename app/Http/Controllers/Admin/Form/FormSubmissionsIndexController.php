<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Admin\BaseController;
use Inertia\Response;

final class FormSubmissionsIndexController extends BaseController
{
    public function __invoke(int $id): Response
    {
        return $this->render('Admin/Forms/Submissions', [
            'formId' => $id,
            'apiUrl' => route('api.v1.form-submissions.list', ['form_id' => $id]),
        ]);
    }
}
