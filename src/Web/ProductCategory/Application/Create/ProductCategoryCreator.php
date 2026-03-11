<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Create;

use Termosalud\Web\ProductCategory\Domain\ProductCategory;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryRepository;

final class ProductCategoryCreator
{
    public function __construct(private readonly ProductCategoryRepository $repository) {}

    public function __invoke(string $status, int $order, array $translations): void
    {
        $category = new ProductCategory(0, $status, $order, $translations);

        $this->repository->save($category);
    }
}
