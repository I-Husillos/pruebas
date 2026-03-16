<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteTreatmentLocalizationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TreatmentLocalizationDeleter $deleter
    ) {}

    public function __invoke(DeleteTreatmentLocalizationCommand $command): void
    {
        $this->deleter->__invoke($command->localizationId());
    }
}
