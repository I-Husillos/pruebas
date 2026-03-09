<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Delete;

use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class TreatmentCategoryDeleter
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
