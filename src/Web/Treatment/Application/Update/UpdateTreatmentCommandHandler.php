<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentUpdater $updater) {}

    public function __invoke(UpdateTreatmentCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->name(),
            $command->slug(),
            $command->description(),
            $command->published(),
            $command->availableMarkets(),
            $command->sortOrder(),
            $command->categoryId(),
            $command->blocksJson()
        );
    }
}
