<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class UpdateArticleCategoryCommandHandler implements CommandHandler
{
    public function __construct(private readonly ArticleCategoryUpdater $updater) {}

    public function __invoke(UpdateArticleCategoryCommand $command): void
    {
        $this->updater->__invoke(
            $command->id(),
            $command->status(),
            $command->order(),
            $command->translations()
        );
    }
}
