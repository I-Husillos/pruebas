<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface ProductRepository
{
    public function save(Product $product): void;

    public function search(int $id): ?Product;

    /** @return Product[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;

    public function remove(int $id): void;
}
