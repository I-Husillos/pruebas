<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Delete;

use Termosalud\Web\Page\Domain\PageRepository;

final class PageRemover
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(int $id): void
    {
        $this->repository->remove($id);
    }
}
