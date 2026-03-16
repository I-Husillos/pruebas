<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Update;

use Termosalud\Web\Treatment\Domain\TreatmentRepository;
use Termosalud\Web\Treatment\Domain\Treatment;

final class TreatmentUpdater
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(
        int $id,
        ?int $treatmentCategoryId,
        string $status,
        array $images,
        array $localizations,
        ?array $relatedProducts = null,
        int $order = 0,
    ): void {
        $treatment = new Treatment(
            $id,
            $treatmentCategoryId,
            $status,
            $images,
            $localizations,
            $relatedProducts,
            $order,
            null,
            null,
            null,
        );

        $this->repository->save($treatment);
    }
}

