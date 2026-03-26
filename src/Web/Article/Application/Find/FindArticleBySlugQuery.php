<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindArticleBySlugQuery implements Query
{
    public function __construct(
        private readonly string $slug,
        private readonly int    $languageId,
        private readonly int    $marketId,
    ) {}

    public function slug(): string      { return $this->slug; }
    public function languageId(): int   { return $this->languageId; }
    public function marketId(): int     { return $this->marketId; }
}