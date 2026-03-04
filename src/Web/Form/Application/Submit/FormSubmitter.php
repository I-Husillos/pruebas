<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Submit;

use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Domain\FormSubmission;

final class FormSubmitter
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(
        string $formKey,
        array $data,
        ?string $ip,
        ?string $userAgent
    ): void {
        $form = $this->repository->findByKey($formKey);

        if (!$form) {
            throw new \InvalidArgumentException("Form not found for key: " . $formKey);
        }

        $submission = new FormSubmission(
            null, // ID auto-generated
            (int) $form->id,
            $data,
            $ip,
            $userAgent,
            'new',
            date('Y-m-d H:i:s')
        );

        $this->repository->saveSubmission($submission);
    }
}
