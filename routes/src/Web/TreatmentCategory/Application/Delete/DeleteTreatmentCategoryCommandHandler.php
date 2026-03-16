<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteTreatmentCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentCategoryDeleter $deleter) {}

    public function __invoke(DeleteTreatmentCategoryCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
