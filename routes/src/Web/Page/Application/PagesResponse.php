<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;

final class PagesResponse implements Response
{
    /** @var PageResponse[] */
    private array $pages;
    private int $total;
    private int $limit;
    private int $offset;

    public function __construct(array $pages, int $total, int $limit, int $offset)
    {
        $this->pages = $pages;
        $this->total = $total;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function toArray(): array
    {
        $perPage = $this->limit > 0 ? $this->limit : 15;
        $currentPage = $perPage > 0 ? (int) floor($this->offset / $perPage) + 1 : 1;
        $totalPages = $perPage > 0 ? (int) ceil($this->total / $perPage) : 1;

        return [
            'records' => array_map(fn(PageResponse $page) => $page->toArray(), $this->pages),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'filtered_records' => $this->total,
            'total_records' => $this->total,
            'per_page' => $perPage,
        ];
    }
}
