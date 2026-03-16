<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindProductCategoryQuery implements Query
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }
}
