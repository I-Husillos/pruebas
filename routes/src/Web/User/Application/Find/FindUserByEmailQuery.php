<?php

declare(strict_types=1);

namespace Termosalud\Web\User\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindUserByEmailQuery implements Query
{
    public function __construct(private readonly string $email) {}

    public function email(): string
    {
        return $this->email;
    }
}
