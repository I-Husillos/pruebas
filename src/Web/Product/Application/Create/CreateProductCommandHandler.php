<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Product\Domain\Product;
use Termosalud\Web\Product\Domain\ProductId;
use Termosalud\Web\Product\Domain\ProductCode;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductCreator $creator
    ) {}

    public function __invoke(CreateProductCommand $command): void
    {
        $this->creator->__invoke(
            new ProductId((int) $command->id()),
            new ProductCode($command->code()),
            $command->name(),
            $command->slug(),
            $command->shortDescription(),
            $command->description(),
            $command->technicalSpecs(),
            $command->images(),
            $command->category(),
            $command->tags(),
            $command->published(),
            $command->publishedAt(),
            $command->availableMarkets(),
            $command->metaSeo(),
            $command->sortOrder()
        );
    }
}
