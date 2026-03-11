<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Update;

use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class TreatmentCategoryUpdater
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(
        int $id,
        string $status,
        int $order,
        array $translations
    ): void {
        $category = new TreatmentCategory($id, $status, $order, $translations);

        $this->repository->save($category);
    }
}
