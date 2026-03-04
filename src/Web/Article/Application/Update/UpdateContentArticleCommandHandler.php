<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateContentArticleCommandHandler implements CommandHandler
{
    public function __construct(private readonly ContentArticleUpdater $updater) {}

    public function __invoke(UpdateContentArticleCommand $command): void
    {
        $this->updater->__invoke($command->id(), $command->type(), $command->title(), $command->slug(), $command->excerpt(), $command->content(), $command->author(), $command->published(), $command->publishedAt());
    }
}
