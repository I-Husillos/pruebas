<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Page\Domain\PageRepository;
use Termosalud\Web\Page\Application\PageResponse;

final class FindPageByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(FindPageByIdQuery $query): ?PageResponse
    {
        $page = $this->repository->search($query->id());
        return $page ? PageResponse::fromPage($page) : null;
    }
}
