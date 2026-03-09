<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentCreator $creator) {}

    public function __invoke(CreateTreatmentCommand $command): void
    {
        $this->creator->__invoke(
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
