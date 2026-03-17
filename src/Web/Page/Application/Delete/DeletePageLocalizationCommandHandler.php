<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeletePageLocalizationCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageLocalizationDeleter $deleter) {}

    public function __invoke(DeletePageLocalizationCommand $command): void
    {
        $this->deleter->__invoke($command->localizationId());
    }
}