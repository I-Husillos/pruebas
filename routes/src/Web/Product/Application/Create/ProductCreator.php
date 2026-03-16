<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Domain\Product;

final class ProductCreator
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(
       ?int $productCategoryId,
       string $code,
       string $status,
       array $images,
       array $localizations,
       ?array $relatedTreatments = null,
       int $order = 0,
    ): void {
        $product = Product::create(
          $productCategoryId,
          $code,
          $status,
          $images,
          $localizations,
          $relatedTreatments,
          $order,
        );

        $this->repository->save($product);
    }
}
