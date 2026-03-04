<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class SearchFormsQuery implements Query
{
    public function __construct(private readonly ?string $search = null) {}

    public function search(): ?string
    {
        return $this->search;
    }
}
