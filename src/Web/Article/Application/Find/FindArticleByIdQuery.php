<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindArticleByIdQuery implements Query
{
    public function __construct(private readonly string $id) {}

    public function id(): string
    {
        return $this->id;
    }
}
