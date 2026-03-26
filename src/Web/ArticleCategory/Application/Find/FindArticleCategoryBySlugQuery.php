<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class FindArticleCategoryBySlugQuery implements Query
{
    private string $slug;
    private int $languageId;
    private int $page;
    private int $perPage;
    private array $filters;

    public function __construct(string $slug, int $languageId, int $page = 1, int $perPage = 12, array $filters = [])
    {
        $this->slug = $slug;
        $this->languageId = $languageId;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->filters = $filters;
    }

    public function slug(): string { return $this->slug; }
    public function languageId(): int { return $this->languageId; }
    public function page(): int { return $this->page; }
    public function perPage(): int { return $this->perPage; }
    public function filters(): array { return $this->filters; }
}
