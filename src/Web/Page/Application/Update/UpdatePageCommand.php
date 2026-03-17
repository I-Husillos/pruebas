<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdatePageCommand implements Command
{
    public function __construct(
        private readonly int    $id,
        private readonly string $status,
        private readonly array  $localizations,
    ) {}

    public function id(): int              { return $this->id; }
    public function status(): string       { return $this->status; }
    public function localizations(): array { return $this->localizations; }
}
