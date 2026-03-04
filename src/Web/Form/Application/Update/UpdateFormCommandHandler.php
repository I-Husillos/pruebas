<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Form\Domain\Form;
use Termosalud\Web\Form\Domain\FormRepository;

final class UpdateFormCommandHandler implements CommandHandler
{
    public function __construct(private readonly FormUpdater $updater) {}

    public function __invoke(UpdateFormCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->name(),
            $command->key(),
            $command->recipientEmail(),
            $command->fields(),
            $command->isActive()
        );
    }
}
