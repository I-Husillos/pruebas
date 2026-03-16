<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindFormByKeyQuery implements Query
{
    public function __construct(private readonly string $key) {}

    public function key(): string
    {
        return $this->key;
    }
}
