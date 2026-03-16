<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Page\Domain\PageRepository;

final class FindPageBySlugQueryHandler implements QueryHandler
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(FindPageBySlugQuery $query): ?\Termosalud\Content\Application\PageResponse
    {
        $page = $this->repository->findBySlug($query->market(), $query->lang(), $query->slug());
        return $page ? \Termosalud\Content\Application\PageResponse::fromPage($page) : null;
    }
}
