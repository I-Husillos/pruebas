<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Treatment\Domain\Treatment;

final class TreatmentResponse implements Response
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

    public static function fromTreatment(Treatment $treatment): self
    {
        return new self(
            $treatment->id(),
            $treatment->treatmentCategoryId(),
            $treatment->status(),
            $treatment->images(),
            $treatment->localizations(),
            $treatment->relatedProducts(),
            $treatment->order(),
            $treatment->createdAt(),
            $treatment->updatedAt(),
            $treatment->deletedAt(),
        );
    }

    public function toArray(): array
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
