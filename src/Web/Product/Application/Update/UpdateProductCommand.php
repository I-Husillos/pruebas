<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateProductCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private ?int $productCategoryId,
        private string $code,
        private string $status,
        private array $images,
        private array $localizations,
        private ?array $relatedTreatments = null,
        private int $order = 0,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function productCategoryId(): ?int { return $this->productCategoryId; }
    public function code(): string { return $this->code; }
    public function status(): string { return $this->status; }
    public function images(): array { return $this->images; }
    public function localizations(): array { return $this->localizations; }
    public function relatedTreatments(): ?array { return $this->relatedTreatments; }
    public function order(): int { return $this->order; }
}
