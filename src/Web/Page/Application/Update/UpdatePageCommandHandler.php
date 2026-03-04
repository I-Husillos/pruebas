<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;
use Exception;

final class UpdatePageCommandHandler implements CommandHandler
{
    public function __construct(private readonly PageUpdater $updater) {}

    public function __invoke(UpdatePageCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
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
