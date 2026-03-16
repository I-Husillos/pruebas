<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserDeleter $deleter) {}

    public function __invoke(DeleteUserCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
