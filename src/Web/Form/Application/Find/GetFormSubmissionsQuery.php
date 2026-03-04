<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class GetFormSubmissionsQuery implements Query
{
    public function __construct(private readonly int $formId) {}

    public function formId(): int
    {
        return $this->formId;
    }
}
