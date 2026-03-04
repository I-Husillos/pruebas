<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Domain;

interface MarketRepository
{
    public function save(Market $market): void;

    public function findByCode(MarketCode $code): ?Market;

    public function findAllActive(): array;

    /** @return Market[] */
    public function searchByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): array;

    public function countByCriteria(\Dba\DddSkeleton\Shared\Domain\Criteria\Criteria $criteria): int;
}
