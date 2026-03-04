<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateProductCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly string $code,
        private readonly array $name,
        private readonly array $slug,
        private readonly ?array $shortDescription = null,
        private readonly ?array $description = null,
        private readonly ?array $technicalSpecs = null,
        private readonly ?array $images = null,
        private readonly ?string $category = null,
        private readonly ?array $tags = null,
        private readonly bool $published = false,
        private readonly ?string $publishedAt = null,
        private readonly ?array $availableMarkets = null,
        private readonly ?array $metaSeo = null,
        private readonly int $sortOrder = 0
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): array
    {
        return $this->name;
    }

    public function slug(): array
    {
        return $this->slug;
    }

    public function shortDescription(): ?array
    {
        return $this->shortDescription;
    }

    public function description(): ?array
    {
        return $this->description;
    }

    public function technicalSpecs(): ?array
    {
        return $this->technicalSpecs;
    }

    public function images(): ?array
    {
        return $this->images;
    }

    public function category(): ?string
    {
        return $this->category;
    }

    public function tags(): ?array
    {
        return $this->tags;
    }

    public function published(): bool
    {
        return $this->published;
    }

    public function publishedAt(): ?string
    {
        return $this->publishedAt;
    }

    public function availableMarkets(): ?array
    {
        return $this->availableMarkets;
    }

    public function metaSeo(): ?array
    {
        return $this->metaSeo;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }
}
