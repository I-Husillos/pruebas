<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindLanguageByCodeQuery implements Query
{
    public function __construct(private readonly string $code) {}

    public function code(): string
    {
        return $this->code;
    }
}
