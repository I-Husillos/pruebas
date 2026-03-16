<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class DeleteArticleLocalizationCommand implements Command
{
    public function __construct(private readonly int $localizationId) {}

    public function localizationId(): int
    {
        return $this->localizationId;
    }
}