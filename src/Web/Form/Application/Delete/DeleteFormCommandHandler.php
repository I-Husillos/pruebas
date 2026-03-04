<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteFormCommandHandler implements CommandHandler
{
    public function __construct(private readonly FormDeleter $deleter) {}

    public function __invoke(DeleteFormCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
