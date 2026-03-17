<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Create;

use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;

final class PageCreator
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(string $status, array $localizations): void
    {
        $this->repository->save(Page::create($status, $localizations));
    }
}
