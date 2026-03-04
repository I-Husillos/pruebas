<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Update;

use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;
use Exception;

final class PageUpdater
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(
        int $id,
        string $marketCode,
        string $languageCode,
        string $slug,
        bool $isActive,
        ?string $seoTitle,
        ?string $seoDescription,
        ?array $blocks
    ): void {
        $existingPage = $this->repository->findById($id);
        if (! $existingPage) {
            throw new Exception('Page not found');
        }

        $updatedPage = new Page(
            $id,
            $marketCode,
            $languageCode,
            $slug,
            $isActive,
            $seoTitle,
            $seoDescription,
            $blocks
        );

        $this->repository->update($updatedPage);
    }
}
