<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;

final class TreatmentCategoryResponse implements Response
{
    public function __construct(
        private readonly int $id,
        private readonly string $status,
        private readonly int $order,
        private readonly array $translations,
        private readonly ?string $createdAt,
        private readonly ?string $updatedAt
    ) {}

    public static function fromCategory(TreatmentCategory $category): self
    {
        $primitives = $category->toPrimitives();

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
            'id' => $this->id,
            'status' => $this->status,
            'order' => $this->order,
            'translations' => $this->translations,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
