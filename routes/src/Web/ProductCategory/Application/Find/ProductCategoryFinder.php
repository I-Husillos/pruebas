<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Find;

use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;
use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryId;

final class ProductCategoryFinder
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(ProductCategoryId $id): ?ProductCategory
    {
        return $this->repository->search($id->value());
    }
}
