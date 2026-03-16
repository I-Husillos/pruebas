<?php

namespace Termosalud\Web\User\Domain;

interface UserRepository
{
    public function save(User $user, ?string $password = null): User;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function delete(int $id): void;

    /** @return User[] */
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;

    public function syncRoles(int $userId, array $roles): void;
}
