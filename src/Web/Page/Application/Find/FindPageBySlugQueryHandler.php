<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Page\Application\PageResponse;
use Termosalud\Web\Page\Domain\PageRepository;

final class FindPageBySlugQueryHandler implements QueryHandler
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(FindPageBySlugQuery $query): ?PageResponse
    {
        $page = $this->repository->findBySlug(
            $query->slug(),
            $query->languageId(),
            $query->marketId(),
        );

        return $page ? PageResponse::fromPage($page) : null;
    }
}
