<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdatePageCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageUpdater $updater) {}

    public function __invoke(UpdatePageCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->status(),
            $command->localizations(),
        );
    }
}
