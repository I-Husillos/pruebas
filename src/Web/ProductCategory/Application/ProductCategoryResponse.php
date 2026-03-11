<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\ProductCategory\Domain\ProductCategory;

final class ProductCategoryResponse implements Response
{
    public function __construct(
        private readonly int $id,
        private readonly string $status,
        private readonly int $order,
        private readonly array $translations,
        private readonly ?string $createdAt,
        private readonly ?string $updatedAt
    ) {}

    public static function fromCategory(ProductCategory $category): self
    {
        return new self(
            $category->id(),
            $category->status(),
            $category->order(),
            $category->translations(),
            $category->createdAt(),
            $category->updatedAt()
        );
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'status'       => $this->status,
            'order'        => $this->order,
            'translations' => $this->translations,
            'created_at'   => $this->createdAt,
            'updated_at'   => $this->updatedAt,
        ];
    }
}
