<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class RemovePageCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageRemover $remover) {}

    public function __invoke(RemovePageCommand $command): void
    {
        $this->remover->__invoke($command->id());
    }
}
