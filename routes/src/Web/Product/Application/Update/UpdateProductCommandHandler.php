<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductUpdater $updater
    ) {}

    public function __invoke(UpdateProductCommand $command): void
    {
        $this->updater->__invoke(
            (int) $command->id(),
            $command->productCategoryId(),
            $command->code(),
            $command->status(),
            $command->images(),
            $command->localizations(),
            $command->relatedTreatments(),
            $command->order(),
        );
    }
}
