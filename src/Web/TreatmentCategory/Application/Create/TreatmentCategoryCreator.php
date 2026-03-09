<?php

declare(strict_types=1);

namespace Termosalud\Web\TreatmentCategory\Application\Create;

use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategory;
use Termosalud\Web\TreatmentCategory\Domain\TreatmentCategoryRepository;

final class TreatmentCategoryCreator
{
    public function __construct(private readonly TreatmentCategoryRepository $repository) {}

    public function __invoke(
        int $id,
        array $name,
        array $slug,
        ?array $description,
        bool $active,
        int $sortOrder
    ): void {
        $category = new TreatmentCategory($id, $name, $slug, $description, $active, $sortOrder);

        $this->repository->save($category);
    }
}
