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
            $command->id(),
            $command->type(),
            $command->title(),
            $command->slug(),
            $command->excerpt(),
            $command->content(),
            $command->author(),
            $command->published(),
            $command->categoryId(),
            $command->publishedAt()
        );
    }
}
