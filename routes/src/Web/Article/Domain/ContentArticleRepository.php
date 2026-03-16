<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Domain;

use Dba\DddSkeleton\Shared\Domain\Criteria\Criteria;

interface ContentArticleRepository
{
    public function save(ContentArticle $article): void;

    public function search(int $id): ?ContentArticle;

    /** @return ContentArticle[] */
    public function searchByCriteria(Criteria $criteria): array;

    public function countByCriteria(Criteria $criteria): int;

    public function remove(int $id): void;

    public function findBySlug(string $slug, int $language, int $market): ?ContentArticle;

    // elimina una localización concreta de un artículo.
    // A diferencia de remove() que borra el artículo entero, este solo borra una fila de article_localizations
    public function removeLocalization(int $localizationId): void;

}
