<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateContentArticleCommand implements Command
{
    public function __construct(
        private int $id,
        private int $articleCategoryId,
        private string $status,
        private array $images,
        private array $localizations,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function articleCategoryId(): int
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
