<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Submit;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class SubmitFormCommand implements Command
{
    public function __construct(
        private readonly string $formKey,
        private readonly array $data,
        private readonly ?string $ip = null,
        private readonly ?string $userAgent = null
    ) {}

    public function formKey(): string
    {
        return $this->formKey;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function ip(): ?string
    {
        return $this->ip;
    }

    public function userAgent(): ?string
    {
        return $this->userAgent;
    }
}
