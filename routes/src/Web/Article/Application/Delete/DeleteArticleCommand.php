<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class DeleteArticleCommand implements Command
{
    public function __construct(private readonly int $id) {}

    public function id(): int
    {
        return $this->id;
    }
}
