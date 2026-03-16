<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface TreatmentCategoryRepository
{
    public function save(TreatmentCategory $category): void;

    /** @return TreatmentCategory[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;

    public function search(int $id): ?TreatmentCategory;

    public function remove(int $id): void;
}
