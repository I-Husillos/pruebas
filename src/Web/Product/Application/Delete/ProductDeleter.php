<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Delete;

use Termosalud\Web\Product\Domain\ProductId;
use Termosalud\Web\Product\Domain\ProductRepository;

final class ProductDeleter
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove(new ProductId($id));
    }
}
