<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class DeleteArticleCategoryCommand implements Command
{
    public function __construct(private readonly int $id) {}

    public function id(): int
    {
        return $this->id;
    }
}
