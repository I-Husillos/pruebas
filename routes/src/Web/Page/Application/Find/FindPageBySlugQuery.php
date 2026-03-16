<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindPageBySlugQuery implements Query
{
    public function __construct(
        private readonly string $market,
        private readonly string $lang,
        private readonly string $slug
    ) {}

    public function market(): string
    {
        return $this->market;
    }

    public function lang(): string
    {
        return $this->lang;
    }

    public function slug(): string
    {
        return $this->slug;
    }
}
