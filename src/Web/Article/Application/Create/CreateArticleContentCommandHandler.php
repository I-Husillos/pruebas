<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateArticleContentCommandHandler implements CommandHandler
{
    public function __construct(private readonly ContentArticleCreator $creator) {}

    public function __invoke(CreateArticleContentCommand $command): void
    {
        $this->creator->__invoke(
            $command->articleCategoryId(),
            $command->status(),
            $command->images(),
            $command->localizations(),
        );
    }
}
