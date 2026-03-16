<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Treatment extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private ?int $treatmentCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
        private ?array $relatedProducts,
        private int $order,
        private ?string $createdAt,
        private ?string $updatedAt,
        private ?string $deletedAt,
    ) {}

    public static function create(
        ?int $treatmentCategoryId,
        string $status,
        array $images,
        array $localizations,
        ?array $relatedProducts = null,
        int $order = 0,
    ): self {
        return new self(
            null,
            $treatmentCategoryId,
            $status,
            $images,
            $localizations,
            $relatedProducts,
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
            $data['treatment_category_id'] ?? null,
            $data['status'] ?? 'draft',
            $data['images'] ?? [],
            $data['localizations'] ?? [],
            $data['related_products'] ?? null,
            (int) ($data['order'] ?? 0),
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['deleted_at'] ?? null,
        );
    }

    public function id(): ?int { return $this->id; }
    public function treatmentCategoryId(): ?int { return $this->treatmentCategoryId; }
    public function status(): string { return $this->status; }
    public function images(): array { return $this->images; }
    public function localizations(): array { return $this->localizations; }
    public function relatedProducts(): ?array { return $this->relatedProducts; }
    public function order(): int { return $this->order; }
    public function createdAt(): ?string { return $this->createdAt; }
    public function updatedAt(): ?string { return $this->updatedAt; }
    public function deletedAt(): ?string { return $this->deletedAt; }

    public function toPrimitives(): array
    {
        return [
            'id'                    => $this->id,
            'treatment_category_id' => $this->treatmentCategoryId,
            'status'                => $this->status,
            'images'                => $this->images,
            'localizations'         => $this->localizations,
            'related_products'      => $this->relatedProducts,
            'order'                 => $this->order,
            'created_at'            => $this->createdAt,
            'updated_at'            => $this->updatedAt,
            'deleted_at'            => $this->deletedAt,
        ];
    }
}
