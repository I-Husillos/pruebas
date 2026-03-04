<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface TreatmentRepository
{
    public function save(Treatment $treatment): void;

    public function search(TreatmentId $id): ?Treatment;

    /** @return Treatment[] */
    public function searchAll(): array;

    /** @return Treatment[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;

    public function remove(TreatmentId $id): void;
}
