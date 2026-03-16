<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateFormCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $key,
        private readonly ?string $recipientEmail,
        private readonly array $fields,
        private readonly bool $isActive
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function recipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
