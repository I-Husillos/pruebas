<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\User\Domain\User;

final class UserResponse implements Response
{
    private int $id;
    private string $name;
    private string $email;
    private array $roles;

    public function __construct(
        int $id,
        string $name,
        string $email,
        array $roles = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->roles = $roles;
    }

    public static function fromUser(User $user): self
    {
        return new self(
            $user->id,
            $user->name,
            $user->email,
            $user->roles ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles
        ];
    }
}
