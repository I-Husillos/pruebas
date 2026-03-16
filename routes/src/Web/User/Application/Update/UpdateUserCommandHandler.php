<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\User\Domain\User;
use Termosalud\Web\User\Domain\UserRepository;

final class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserUpdater $updater) {}

    public function __invoke(UpdateUserCommand $command): void
    {
        $this->updater->__invoke($command->id(), $command->name(), $command->email(), $command->password(), $command->roles());
    }
}
