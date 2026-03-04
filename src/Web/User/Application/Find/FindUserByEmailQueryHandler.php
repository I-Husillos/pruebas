<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\User\Domain\UserRepository;
use Termosalud\Web\User\Application\UserResponse;

final class FindUserByEmailQueryHandler implements QueryHandler
{
    public function __construct(private readonly UserRepository $repository) {}

    public function __invoke(FindUserByEmailQuery $query): ?UserResponse
    {
        $user = $this->repository->findByEmail($query->email());

        return $user ? UserResponse::fromUser($user) : null;
    }
}
