<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;

final class FormSubmissionsResponse implements Response
{
    /** @var FormSubmissionResponse[] */
    private readonly array $submissions;
    private readonly int $total;
    private readonly int $limit;
    private readonly int $offset;

    public function __construct(array $submissions, int $total, int $limit, int $offset)
    {
        $this->submissions = $submissions;
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
            'records' => array_map(fn(FormSubmissionResponse $response) => $response->toArray(), $this->submissions),
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'filtered_records' => $this->total,
            'total_records' => $this->total,
            'per_page' => $perPage,
        ];
    }
}
