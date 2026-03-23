<?php

namespace Src\Web\Slug\Domain;

use DomainException;

class SlugResolver implements SlugResolverInterface
{
    /**
     * @var array<int,object> Lista de resolvers de slugs (Page, Article, Product, etc.)
     */
    private array $resolvers;

    public function __construct(PageSlugResolver $pageSlugResolver)
    {
        // Por ahora solo soporta páginas, pero es extensible
        $this->resolvers = [
            ResolvedContent::TYPE_PAGE => $pageSlugResolver,
            // Futuro: 'article' => $articleSlugResolver, etc.
        ];
    }

    /**
     * Resuelve un slug único global y retorna un DTO ResolvedContent.
     *
     * @param string $slug
     * @param int $marketId
     * @param int $languageId
     * @return ResolvedContent
     * @throws DomainException
     */
    public function resolve(string $slug, int $marketId, int $languageId): ResolvedContent
    {
        // 1. Buscar página
        $page = $this->resolvers[ResolvedContent::TYPE_PAGE]->resolve($slug, $marketId, $languageId);
        if ($page) {
            return new ResolvedContent(ResolvedContent::TYPE_PAGE, $page);
        }

        throw new DomainException('Slug no encontrado.');
    }
}
