<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreatePageCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageCreator $creator) {}

    public function __invoke(CreatePageCommand $command): void
    {
        $this->creator->__invoke(
            $command->status(),
            $command->localizations(),
        );
    }
}
