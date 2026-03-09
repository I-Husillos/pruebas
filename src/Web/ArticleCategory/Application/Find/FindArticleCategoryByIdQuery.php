<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindArticleCategoryByIdQuery implements Query
{
    public function __construct(private readonly int $id) {}

    public function id(): int
    {
        return $this->id;
    }
}
