<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdatePageCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly string $marketCode,
        private readonly string $languageCode,
        private readonly string $slug,
        private readonly bool $isActive,
        private readonly ?string $seoTitle,
        private readonly ?string $seoDescription,
        private readonly array $blocks
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function marketCode(): string
    {
        return $this->marketCode;
    }

    public function languageCode(): string
    {
        return $this->languageCode;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function seoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function seoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function blocks(): array
    {
        return $this->blocks;
    }
}
