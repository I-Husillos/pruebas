<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductCreator $creator
    ) {}

    public function __invoke(CreateProductCommand $command): void
    {
        $this->creator->__invoke(
            $command->productCategoryId(),
            $command->code(),
            $command->status(),
            $command->images(),
            $command->localizations(),
            $command->relatedTreatments(),
            $command->order(),
        );
    }
}
