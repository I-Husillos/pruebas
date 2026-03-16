<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindMarketByCodeQuery implements Query
{
    public function __construct(private readonly string $code) {}

    public function code(): string
    {
        return $this->code;
    }
}
