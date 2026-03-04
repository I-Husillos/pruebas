<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteArticleCommandHandler implements CommandHandler
{
    public function __construct(private readonly ArticleDeleter $deleter) {}

    public function __invoke(DeleteArticleCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
