<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\User\Domain\User;
use Termosalud\Web\User\Domain\UserRepository;

final class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserCreator $creator) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $this->creator->__invoke($command->name(), $command->email(), $command->password(), $command->roles());
    }
}
