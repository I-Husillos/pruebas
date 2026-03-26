<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Treatment\Application\TreatmentResponse;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class FindTreatmentBySlugQueryHandler implements QueryHandler
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(FindTreatmentBySlugQuery $query): ?TreatmentResponse
    {
        $treatment = $this->repository->findBySlug(
            $query->slug(),
            $query->languageId(),
            $query->marketId(),
        );

        return $treatment ? TreatmentResponse::fromTreatment($treatment) : null;
    }
}