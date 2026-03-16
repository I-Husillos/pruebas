<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class DeleteArticleLocalizationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ArticleLocalizationDeleter $deleter
    ) {}

    public function __invoke(DeleteArticleLocalizationCommand $command): void
    {
        $this->deleter->__invoke($command->localizationId());
    }
}