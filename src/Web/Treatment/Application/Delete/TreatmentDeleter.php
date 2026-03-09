<?php

declare(strict_types=1);

namespace Termosalud\Web\Treatment\Application\Delete;

use Termosalud\Web\Treatment\Domain\TreatmentRepository;

final class TreatmentDeleter
{
    public function __construct(private readonly TreatmentRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
