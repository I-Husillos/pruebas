<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteProductLocalizationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductLocalizationDeleter $deleter
    ) {}

    public function __invoke(DeleteProductLocalizationCommand $command): void
    {
        $this->deleter->__invoke($command->localizationId());
    }
}
