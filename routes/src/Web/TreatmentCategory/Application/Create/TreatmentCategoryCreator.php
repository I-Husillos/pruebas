<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Create;

use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class TreatmentCategoryCreator
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(
        string $status,
        int $order,
        array $translations
    ): void {
        $category = new TreatmentCategory(0, $status, $order, $translations);

        $this->repository->save($category);
    }
}
