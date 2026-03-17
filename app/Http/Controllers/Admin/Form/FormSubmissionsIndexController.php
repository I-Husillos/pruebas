<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Form;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Form;
use Inertia\Response;

final class FormSubmissionsIndexController extends BaseController
{
    public function __invoke(int $id): Response
    {
        return $this->render('Admin/Forms/Submissions', [
            'form' => Form::query()->findOrFail($id)->toArray(),
            'submissions' => Form::query()->findOrFail($id),
            'formId' => $id,
            'apiUrl' => route('api.v1.form-submissions.list', ['id' => $id]),
        ]);
    }
}
