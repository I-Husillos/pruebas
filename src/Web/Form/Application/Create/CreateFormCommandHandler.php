<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Form\Domain\Form;
use Termosalud\Web\Form\Domain\FormRepository;

final class CreateFormCommandHandler implements CommandHandler
{
    public function __construct(private readonly FormCreator $creator) {}

    public function __invoke(CreateFormCommand $command): void
    {
        $this->creator->__invoke(
            $command->name(),
            $command->key(),
            $command->recipientEmail(),
            $command->fields(),
            $command->isActive()
        );
    }
}
