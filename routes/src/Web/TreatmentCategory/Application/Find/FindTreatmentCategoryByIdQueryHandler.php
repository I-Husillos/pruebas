<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\TreatmentCategory\Application\TreatmentCategoryResponse;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class FindTreatmentCategoryByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(FindTreatmentCategoryByIdQuery $query): ?TreatmentCategoryResponse
    {
        $category = $this->repository->search($query->id());

        return $category ? TreatmentCategoryResponse::fromCategory($category) : null;
    }
}
