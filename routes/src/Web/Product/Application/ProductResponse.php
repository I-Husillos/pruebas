<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Product\Domain\Product;

final class ProductResponse implements Response
{
    public function __construct(
        private readonly ?int $id,
        private ?int $productCategoryId,
        private string $code,
        private string $status,
        private array $images,
        private array $localizations,
        private ?array $relatedTreatments,
        private int $order,
        private ?string $createdAt,
        private ?string $updatedAt,
        private ?string $deletedAt,
    ) {}

    public static function fromProduct(Product $product): self
    {
        return new self(
            $product->id(),
            $product->productCategoryId(),
            $product->code(),
            $product->status(),
            $product->images(),
            $product->localizations(),
            $product->relatedTreatments(),
            $product->order(),
            $product->createdAt(),
            $product->updatedAt(),
            $product->deletedAt(),
        );
    }

    public function toArray(): array
    {
        return [
            'id'                  => $this->id,
            'product_category_id' => $this->productCategoryId,
            'code'                => $this->code,
            'status'              => $this->status,
            'images'              => $this->images,
            'localizations'       => $this->localizations,
            'related_treatments'  => $this->relatedTreatments,
            'order'               => $this->order,
            'created_at'          => $this->createdAt,
            'updated_at'          => $this->updatedAt,
            'deleted_at'          => $this->deletedAt,
        ];
    }
}
