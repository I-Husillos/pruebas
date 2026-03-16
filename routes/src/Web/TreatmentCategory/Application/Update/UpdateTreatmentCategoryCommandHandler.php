<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateTreatmentCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentCategoryUpdater $updater) {}

    public function __invoke(UpdateTreatmentCategoryCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->status(),
            $command->order(),
            $command->translations()
        );
    }
}
