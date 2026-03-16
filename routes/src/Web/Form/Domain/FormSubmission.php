<?php

namespace Termosalud\Web\Form\Domain;

class FormSubmission
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $formId,
        public readonly array $data,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
        public readonly string $status = 'new',
        public readonly ?string $createdAt = null
    ) {}
}
