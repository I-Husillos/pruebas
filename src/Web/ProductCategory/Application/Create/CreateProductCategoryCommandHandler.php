<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateProductCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProductCategoryCreator $creator) {}

    public function __invoke(CreateProductCategoryCommand $command): void
    {
        $this->creator->__invoke(
            $command->id(),
            $command->name(),
            $command->slug(),
            $command->description(),
            $command->active(),
            $command->sortOrder(),
        );
    }
}
