<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateArticleCategoryCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly array $name,
        private readonly array $slug,
        private readonly ?array $description = null,
        private readonly bool $active = false,
        private readonly int $sortOrder = 0
    ) {}

    public function id(): int
    {
        return $this->id;
    }

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

    public function active(): bool
    {
        return $this->active;
    }

    public function sortOrder(): int
    {
        return $this->sortOrder;
    }
}