<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateProductCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProductCategoryUpdater $updater) {}

    public function __invoke(UpdateProductCategoryCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->name(),
            $command->slug(),
            $command->description(),
            $command->active(),
            $command->sortOrder(),
        );
    }
}
