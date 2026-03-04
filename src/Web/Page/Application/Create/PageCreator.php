<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Application\Create;

use Termosalud\Web\Page\Domain\Page;
use Termosalud\Web\Page\Domain\PageRepository;

final class PageCreator
{
    public function __construct(private readonly PageRepository $repository) {}

    public function __invoke(
        string $marketCode,
        string $languageCode,
        string $slug,
        bool $isActive,
        ?string $seoTitle,
        ?string $seoDescription,
        ?array $blocks
    ): void {
        $page = Page::create(
            $marketCode,
            $languageCode,
            $slug,
            $isActive,
            $seoTitle,
            $seoDescription,
            $blocks
        );

        $this->repository->save($page);
    }
}
