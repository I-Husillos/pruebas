<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Update;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Domain\Product;

final class ProductUpdater
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(
        int $id,
        ?int $productCategoryId,
        string $code,
        string $status,
        array $images,
        array $localizations,
        ?array $relatedTreatments = null,
        int $order = 0,
    ): void {
        $product = new Product(
            $id,
            $productCategoryId,
            $code,
            $status,
            $images,
            $localizations,
            $relatedTreatments,
            $order,
            null,
            null,
            null,
        );

        $this->repository->save($product);
    }
}
