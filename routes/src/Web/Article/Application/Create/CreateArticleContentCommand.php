<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreateArticleContentCommand implements Command
{
    public function __construct(
        private ?int $articleCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
    ) {}

    public function articleCategoryId(): ?int
    {
        return $this->articleCategoryId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function images(): array
    {
        return $this->images;
    }

    public function localizations(): array
    {
        return $this->localizations;
    }
}