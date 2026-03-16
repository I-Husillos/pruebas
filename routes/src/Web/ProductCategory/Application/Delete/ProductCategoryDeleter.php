<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Delete;

use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;

final class ProductCategoryDeleter
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
