<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Update;

use Termosalud\Web\Product\Domain\ProductRepository;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductCode;

final class ProductUpdater
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(
        int $id,
        ProductCode $code,
        array $name,
        array $slug,
        ?array $shortDescription,
        ?array $description,
        ?array $technicalSpecs,
        ?array $images,
        ?int $categoryId,
        ?array $categoryName,
        ?array $tags,
        ?bool $published,
        ?string $publishedAt,
        ?array $availableMarkets,
        ?array $metaSeo,
        ?int $sortOrder
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
            $categoryId,
            $categoryName,
            $tags,
            $published ?? false,
            $publishedAt,
            $availableMarkets,
            $metaSeo,
            $sortOrder ?? 0
        );

        $this->repository->save($product);
    }
}
