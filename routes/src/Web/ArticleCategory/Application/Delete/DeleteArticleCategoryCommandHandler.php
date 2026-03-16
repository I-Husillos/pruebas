<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteArticleCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly ArticleCategoryDeleter $deleter) {}

    public function __invoke(DeleteArticleCategoryCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
