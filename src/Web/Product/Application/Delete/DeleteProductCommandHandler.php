<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Product\Domain\ProductId;
use Termosalud\Web\Product\Domain\ProductRepository;
use Exception;

final class DeleteProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function __invoke(DeleteProductCommand $command): void
    {
        $productId = new ProductId($command->id());

        $product = $this->repository->search($productId);

        if ($product === null) {
            throw new Exception("Product not found: {$command->id()}");
        }

        $this->repository->remove($productId);
    }
}
