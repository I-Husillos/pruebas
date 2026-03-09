<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Update;

use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;

final class ProductCategoryUpdater
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(
        int $id,
        array $name,
        array $slug,
        ?array $description,
        bool $active,
        int $sortOrder
    ): void {
        $category = new ProductCategory($id, $name, $slug, $description, $active, $sortOrder);

        $this->repository->save($category);
    }
}
