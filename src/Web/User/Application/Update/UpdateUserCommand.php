<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateUserCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly ?string $password = null,
        private readonly array $roles = []
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
