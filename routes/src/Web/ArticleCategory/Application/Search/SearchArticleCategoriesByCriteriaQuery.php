<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Search;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Query;

final class SearchArticleCategoriesByCriteriaQuery implements Query
{
    public function __construct(
        private readonly array $filters,
        private readonly ?string $orderBy = null,
        private readonly ?string $orderType = null,
        private readonly ?int $limit = null,
        private readonly ?int $offset = null
    ) {}

    public function filters(): array
    {
        return $this->filters;
    }

    public function orderBy(): ?string
    {
        return $this->orderBy;
    }

    public function orderType(): ?string
    {
        return $this->orderType;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}
