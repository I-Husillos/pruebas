<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteProductCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProductDeleter $deleter) {}
    public function __invoke(DeleteProductCommand $command): void
    {
        $this->deleter->__invoke($command->id());
    }
}
