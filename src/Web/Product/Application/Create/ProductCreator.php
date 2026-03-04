<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductId;
use Termosalud\Web\Product\Domain\ProductCode;

final class ProductCreator
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(
        ProductId $id,
        ProductCode $code,
        array $name,
        array $slug,
        ?array $shortDescription,
        ?array $description,
        ?array $technicalSpecs,
        ?array $images,
        ?string $category,
        ?array $tags,
        bool $published,
        ?string $publishedAt,
        ?array $availableMarkets,
        ?array $metaSeo,
        int $sortOrder
    ): void {
        $product = Product::create(
            $id,
            $code,
            $name,
            $slug,
            $shortDescription,
            $description,
            $technicalSpecs,
            $images,
            $category,
            $tags,
            $published,
            $publishedAt,
            $availableMarkets,
            $metaSeo,
            $sortOrder
        );

        $this->repository->save($product);
    }
}
