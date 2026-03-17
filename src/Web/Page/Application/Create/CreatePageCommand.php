<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Create;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class CreatePageCommand implements Command
{
    public function __construct(
        private readonly string $status,
        private readonly array  $localizations,
    ) {}

    public function status(): string       { return $this->status; }
    public function localizations(): array { return $this->localizations; }
}
