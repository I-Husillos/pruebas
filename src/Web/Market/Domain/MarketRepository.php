<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface MarketRepository
{
    public function save(Market $market): void;

    public function search(int $id): ?Market;

    public function remove(int $id): void;

    public function findByCode(MarketCode $code): ?Market;

    public function findAllActive(): array;

    /** @return Market[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
