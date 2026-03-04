<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class ArticleCategory extends AggregateRoot
{
    private int $id;
    private array $name;
    private array $slug;
    private ?array $description;
    private bool $active;
    private int $sortOrder;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        int $id,
        array $name,
        array $slug,
        ?array $description,
        bool $active,
        int $sortOrder,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->active = $active;
        $this->sortOrder = $sortOrder;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['name'] ?? [],
            $data['slug'] ?? [],
            $data['description'] ?? null,
            (bool) ($data['active'] ?? false),
            (int) ($data['sort_order'] ?? 0),
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'active' => $this->active,
            'sort_order' => $this->sortOrder,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
