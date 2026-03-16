<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Create;

use Termosalud\Web\Treatment\Domain\TreatmentRepository;
use Termosalud\Web\Treatment\Domain\Treatment;

final class TreatmentCreator
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(
        ?int $treatmentCategoryId,
        string $status,
        array $images,
        array $localizations,
        ?array $relatedProducts = null,
        int $order = 0,
    ): void {
        $treatment = Treatment::create(
            $treatmentCategoryId,
            $status,
            $images,
            $localizations,
            $relatedProducts,
            $order,
        );

        $this->repository->save($treatment);
    }
}

