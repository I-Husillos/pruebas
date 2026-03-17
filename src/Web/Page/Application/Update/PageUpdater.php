<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;

final class PageUpdater
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(int $id, string $status, array $localizations): void
    {
        $page = Page::fromPrimitives([
            'id'            => $id,
            'status'        => $status,
            'localizations' => $localizations,
        ]);

        $this->repository->save($page);
    }
}
