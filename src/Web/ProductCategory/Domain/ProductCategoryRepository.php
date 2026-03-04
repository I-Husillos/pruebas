<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface ProductCategoryRepository
{
    public function search(ProductCategoryId $id): ?ProductCategory;

    /** @return ProductCategory[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;
}
