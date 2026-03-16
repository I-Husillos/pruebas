<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateProductCommand implements Command
{
    public function __construct(
        private ?int $productCategoryId,
        private string $code,
        private string $status,
        private array $images,
        private array $localizations,
        private ?array $relatedTreatments = null,
        private int $order = 0,
    ) {}

    public function productCategoryId(): ?int { return $this->productCategoryId; }
    public function code(): string { return $this->code; }
    public function status(): string { return $this->status; }
    public function images(): array { return $this->images; }
    public function localizations(): array { return $this->localizations; }
    public function relatedTreatments(): ?array { return $this->relatedTreatments; }
    public function order(): int
    { return $this->order; }
}
