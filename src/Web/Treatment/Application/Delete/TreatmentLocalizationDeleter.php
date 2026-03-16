<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Delete;

use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class TreatmentLocalizationDeleter
{
    public function __construct(
        private readonly TreatmentRepository $repository
    ) {}

    public function __invoke(int $localizationId): void
    {
        $this->repository->removeLocalization($localizationId);
    }
}
