<?php

declare(strict_types=1);

namespace Termosalud\Web\Product\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Product\Application\ProductResponse;
use Termosalud\Web\Product\Domain\ProductRepository;

final class FindProductQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function __invoke(FindProductQuery $query): ?ProductResponse
    {
        $product = $this->repository->search($query->id());

        return $product ? ProductResponse::fromProduct($product) : null;
    }
}
