<?php

namespace Src\Web\Slug\Domain;

use Termosalud\Web\Page\Domain\PageRepository;

class PageSlugResolver
{
    public function __construct(private readonly PageRepository $pageRepository) {}

    /**
     * Busca una página por slug, market y language.
     * Devuelve la entidad Page o null si no existe.
     */
    public function resolve(string $slug, int $marketId, int $languageId): ?object
    {
        return $this->pageRepository->findBySlug($slug, $languageId, $marketId);
    }
}
