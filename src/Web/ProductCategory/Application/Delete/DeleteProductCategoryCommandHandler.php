<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteProductCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProductCategoryDeleter $deleter) {}

    public function __invoke(DeleteProductCategoryCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
