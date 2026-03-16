<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;

final class CreatePageCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageCreator $creator) {}

    public function __invoke(CreatePageCommand $command): void
    {
        $this->creator->__invoke(
            $command->marketCode(),
            $command->languageCode(),
            $command->slug(),
            $command->isActive(),
            $command->seoTitle(),
            $command->seoDescription(),
            $command->blocks()
        );
    }
}
