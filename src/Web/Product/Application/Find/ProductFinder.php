<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Find;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductId;

final class ProductFinder
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(ProductId $id): ?Product
    {
        return $this->repository->search($id);
    }
}
