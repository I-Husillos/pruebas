<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Delete;

use Termosalud\Web\User\Domain\UserRepository;

final class UserDeleter
{
    public function __construct(private readonly UserRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->delete($id);
    }
}
