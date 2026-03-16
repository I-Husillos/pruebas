<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Form\Domain\FormSubmission;

final class FormSubmissionResponse implements Response
{
    public function __construct(
        private readonly int $id,
        private readonly int $formId,
        private readonly array $data,
        private readonly ?string $ipAddress,
        private readonly ?string $userAgent,
        private readonly string $status,
        private readonly ?string $createdAt
    ) {}

    public static function fromSubmission(FormSubmission $submission): self
    {
        return new self(
            $submission->id,
            $submission->formId,
            $submission->data,
            $submission->ipAddress,
            $submission->userAgent,
            $submission->status,
            $submission->createdAt
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'form_id' => $this->formId,
            'data' => $this->data,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
