<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Delete;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class DeleteProductLocalizationCommand implements Command
{
    public function __construct(private readonly int $localizationId) {}

    public function localizationId(): int
    {
        return $this->localizationId;
    }
}
