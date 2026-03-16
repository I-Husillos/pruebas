<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Domain;

use Dba\DddSkeleton\Shared\Domain\ValueObject\StringValueObject;

final class ProductCode extends StringValueObject
{
    public function __construct(string $value)
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            throw new \InvalidArgumentException('Product code cannot be empty');
        }

        parent::__construct($trimmed);
    }
}
