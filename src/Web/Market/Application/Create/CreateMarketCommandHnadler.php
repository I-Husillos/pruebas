<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateMarketCommandHandler implements CommandHandler
{
    public function __construct(private readonly MarketCreator $creator) {}

    public function __invoke(CreateMarketCommand $command): void
    {
        $this->creator->__invoke(
            $command->code(),
            $command->name(),
            $command->region(),
            $command->defaultLanguage(),
            $command->enabledLanguages(),
            $command->active(),
            $command->priority()
        );
    }
}