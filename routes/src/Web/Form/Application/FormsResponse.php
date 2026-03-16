<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;

final class FormsResponse implements Response
{
    /** @var FormResponse[] */
    private readonly array $forms;
    private readonly int $total;
    private readonly int $limit;
    private readonly int $offset;

    public function __construct(array $forms, int $total, int $limit, int $offset)
    {
        $this->forms = $forms;
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
            'records' => array_map(fn(FormResponse $form) => $form->toArray(), $this->forms),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'filtered_records' => $this->total,
            'total_records' => $this->total,
            'per_page' => $perPage,
        ];
    }
}
