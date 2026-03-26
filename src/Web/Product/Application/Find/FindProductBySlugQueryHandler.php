<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Product\Application\Find\FindProductBySlugQuery;
use Termosalud\Web\Product\Application\ProductResponse;
use Termosalud\Web\Product\Domain\ProductRepository;

final class FindProductBySlugQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(FindProductBySlugQuery $query): ?ProductResponse
    {
        $product = $this->repository->findBySlug(
            $query->slug(),
            $query->languageId(),
            $query->marketId(),
        );

        return $product ? ProductResponse::fromProduct($product) : null;
    }
}
