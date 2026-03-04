<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Delete;

use Termosalud\Web\Form\Domain\FormRepository;

final class FormDeleter
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
