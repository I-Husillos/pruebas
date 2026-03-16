<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Product extends AggregateRoot
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

    public static function create(
        ?int $productCategoryId,
        string $code,
        string $status,
        array $images,
        array $localizations,
        ?array $relatedTreatments = null,
        int $order = 0,
    ): self {
        return new self(
            null,
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
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['product_category_id'] ?? null,
            $data['code'],
            $data['status'] ?? 'draft',
            $data['images'] ?? [],
            $data['localizations'] ?? [],
            $data['related_treatments'] ?? null,
            (int) ($data['order'] ?? 0),
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['deleted_at'] ?? null,
        );
    }

    public function id(): ?int { return $this->id; }
    public function productCategoryId(): ?int { return $this->productCategoryId; }
    public function code(): string { return $this->code; }
    public function status(): string { return $this->status; }
    public function images(): array { return $this->images; }
    public function localizations(): array { return $this->localizations; }
    public function relatedTreatments(): ?array { return $this->relatedTreatments; }
    public function order(): int { return $this->order; }
    public function createdAt(): ?string { return $this->createdAt; }
    public function updatedAt(): ?string { return $this->updatedAt; }
    public function deletedAt(): ?string { return $this->deletedAt; }

    public function toPrimitives(): array
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
