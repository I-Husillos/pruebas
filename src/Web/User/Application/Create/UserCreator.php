<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Create;

use Termosalud\Web\User\Domain\User;
use Termosalud\Web\User\Domain\UserRepository;

final class UserCreator
{
    public function __construct(private readonly UserRepository $repository) {}

    public function __invoke(string $name, string $email, string $password, array $roles = []): void
    {
        $user = new User(null, $name, $email);
        $savedUser = $this->repository->save($user, $password);

        if (! empty($roles)) {
            $this->repository->syncRoles($savedUser->id, $roles);
        }
    }
}
