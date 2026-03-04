<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Treatment\Application\TreatmentResponse;
use Termosalud\Web\Treatment\Domain\TreatmentId;
use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class FindTreatmentQueryHandler implements QueryHandler
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(FindTreatmentQuery $query): ?TreatmentResponse
    {
        $treatment = $this->repository->search(new TreatmentId((int) $query->id()));

        return $treatment ? TreatmentResponse::fromTreatment($treatment) : null;
    }
}
