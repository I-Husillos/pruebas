<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Update;

use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;

final class ProductCategoryUpdater
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(int $id, string $status, int $order, array $translations): void
    {
        $category = new ProductCategory($id, $status, $order, $translations);

        $this->repository->save($category);
    }
}
