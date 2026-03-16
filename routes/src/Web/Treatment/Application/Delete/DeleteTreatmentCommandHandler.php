<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteTreatmentCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentDeleter $deleter) {}

    public function __invoke(DeleteTreatmentCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
