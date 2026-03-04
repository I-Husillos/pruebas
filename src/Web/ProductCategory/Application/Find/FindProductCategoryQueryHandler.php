<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\ProductCategory\Application\ProductCategoryResponse;
use Termosalud\Web\ProductCategory\Domain\ProductCategoryId;

final class FindProductCategoryQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ProductCategoryFinder $finder
    ) {}

    public function __invoke(FindProductCategoryQuery $query): ?ProductCategoryResponse
    {
        $productCategory = $this->finder->__invoke(new ProductCategoryId($query->id()));

        return $productCategory ? ProductCategoryResponse::fromCategory($productCategory) : null;
    }
}
