<?php

namespace Src\Web\Slug\Domain;

interface SlugResolverInterface
{
    /**
     * Resuelve un slug único global y retorna la entidad correspondiente.
     *
     * @param string $slug
     * @param string $market
     * @param string $language
     * @return mixed  // Puede ser Page, Product, Article, Category, etc.
     * @throws \DomainException Si no se encuentra el slug o no es válido.
     */
    public function resolve(string $slug, int $market, int $language);
}
