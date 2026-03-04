<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateUserCommand implements Command
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $password,
        private readonly array $roles = []
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
