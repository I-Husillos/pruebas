<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateTreatmentCommand implements Command
{
    public function __construct(
        private readonly array $name,
        private readonly array $slug,
        private readonly ?array $description = null,
        private readonly bool $published = false,
        private readonly ?array $availableMarkets = null,
        private readonly int $sortOrder = 0,
        private readonly ?int $categoryId = null,
        private readonly ?array $blocksJson = null
    ) {}

    public function name(): array
    {
        return $this->name;
    }

    public function slug(): array
    {
        return $this->slug;
    }

    public function description(): ?array
    {
        return $this->description;
    }

    public function published(): bool
    {
        return $this->published;
    }

    public function availableMarkets(): ?array
    {
        return $this->availableMarkets;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }

    public function categoryId(): ?int
    {
        return $this->categoryId;
    }

    public function blocksJson(): ?array
    {
        return $this->blocksJson;
    }
}
