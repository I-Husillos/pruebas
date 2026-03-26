<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindArticleCategoryBySlugQuery implements Query
{
    public function __construct(
        private readonly string $slug,
        private readonly int    $languageId,
        private readonly int    $page,
        private readonly int    $perPage,
    ) {}

    public function slug(): string    { return $this->slug; }
    public function languageId(): int { return $this->languageId; }
    public function page(): int       { return $this->page; }
    public function perPage(): int    { return $this->perPage; }
}
