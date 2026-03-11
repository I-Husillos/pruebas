<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class ProductCategory extends AggregateRoot
{
    private int $id;
    private string $status;
    private int $order;
    /** @var array<int, array{language_id: int, title: string, description: ?string, slug: string, seo_metadata: ?array}> */
    private array $translations;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        int $id,
        string $status,
        int $order,
        array $translations = [],
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id           = $id;
        $this->status       = $status;
        $this->order        = $order;
        $this->translations = $translations;
        $this->createdAt    = $createdAt;
        $this->updatedAt    = $updatedAt;
    }

    public function id(): int
    {
        return $this->id;
    }
    public function status(): string
    {
        return $this->status;
    }
    public function order(): int
    {
        return $this->order;
    }
    public function translations(): array
    {
        return $this->translations;
    }
    public function createdAt(): ?string
    {
        return $this->createdAt;
    }
    public function updatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['status'] ?? 'active',
            (int) ($data['order'] ?? 0),
            $data['translations'] ?? [],
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function toPrimitives(): array
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
