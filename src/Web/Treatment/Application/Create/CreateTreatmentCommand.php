<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateTreatmentCommand implements Command
{
    public function __construct(
        private ?int $treatmentCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
        private ?array $relatedProducts = null,
        private int $order = 0,
    ) {}

    public function treatmentCategoryId(): ?int { return $this->treatmentCategoryId; }
    public function status(): string { return $this->status; }
    public function images(): array { return $this->images; }
    public function localizations(): array { return $this->localizations; }
    public function relatedProducts(): ?array { return $this->relatedProducts; }
    public function order(): int { return $this->order; }
}

