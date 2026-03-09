<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;

final class TreatmentCategoryResponse implements Response
{
    public function __construct(
        private readonly int $id,
        private readonly array $name,
        private readonly array $slug,
        private readonly ?array $description,
        private readonly bool $active,
        private readonly int $sortOrder,
        private readonly ?string $createdAt,
        private readonly ?string $updatedAt
    ) {}

    public static function fromCategory(TreatmentCategory $category): self
    {
        $primitives = $category->toPrimitives();

        return new self(
            $primitives['id'],
            $primitives['name'],
            $primitives['slug'],
            $primitives['description'],
            $primitives['active'],
            $primitives['sort_order'],
            $primitives['created_at'],
            $primitives['updated_at']
        );
    }

    public function toArray(): array
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
