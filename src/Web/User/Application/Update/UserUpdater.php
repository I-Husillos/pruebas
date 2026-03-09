<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Update;

use Termosalud\Web\User\Domain\User;
use Termosalud\Web\User\Domain\UserRepository;

final class UserUpdater
{
    public function __construct(private readonly UserRepository $repository) {}

    public function __invoke(int $id, string $name, string $email, ?string $password, array $roles = []): void
    {

        \Illuminate\Support\Facades\Log::info('UserUpdater id: ' . $id . ' type: ' . gettype($id));
        $user = new User($id, $name, $email);
        $this->repository->save($user, $password);

        if (! empty($roles)) {
            $this->repository->syncRoles($id, $roles);
        }
    }
}
