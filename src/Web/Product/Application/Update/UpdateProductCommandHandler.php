<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductCode;

final class UpdateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductUpdater $updater
    ) {}

    public function __invoke(UpdateProductCommand $command): void
    {
        $this->updater->__invoke(
            (int) $command->id(),
            new ProductCode($command->code()),
            $command->name(),
            $command->slug(),
            $command->shortDescription(),
            $command->description(),
            $command->technicalSpecs(),
            $command->images(),
            $command->categoryId(),
            $command->categoryName(),
            $command->tags(),
            $command->published(),
            $command->publishedAt(),
            $command->availableMarkets(),
            $command->metaSeo(),
            $command->sortOrder()
        );
    }
}
