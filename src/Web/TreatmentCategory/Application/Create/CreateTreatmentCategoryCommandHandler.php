<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateTreatmentCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly TreatmentCategoryCreator $creator) {}

    public function __invoke(CreateTreatmentCategoryCommand $command): void
    {
        $this->creator->__invoke(
            $command->status(),
            $command->order(),
            $command->translations()
        );
    }
}
