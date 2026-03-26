<?php

declare(strict_types=1);

namespace Termosalud\Web\ArticleCategory\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\ArticleCategory\Application\ArticleCategoryResponse;
use Termosalud\Web\ArticleCategory\Application\Find\FindArticleCategoryBySlugQuery;
use Termosalud\Web\ArticleCategory\Domain\ArticleCategoryRepository;
use Termosalud\Web\Article\Domain\ContentArticle;

final class FindArticleCategoryBySlugQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ArticleCategoryRepository $repository
    ) {}

    public function __invoke(FindArticleCategoryBySlugQuery $query): ?array
    {
        $category = $this->repository->findBySlug(
            $query->slug(),
            $query->languageId(),
        );

        if (!$category) {
            return null;
        }

        // Artículos paginados de esta categoría
        $perPage = $query->perPage();
        $offset  = ($query->page() - 1) * $perPage;

        $articlesQuery = ContentArticle::where('article_category_id', $category->id())
            ->where('status', 'published')
            ->with('localizations');

        // Aplicar filtros dinámicos
        foreach ($query->filters() as $key => $value) {
            switch ($key) {
                case 'precio':
                    // Suponiendo que value es array [min, max]
                    if (is_array($value) && count($value) === 2) {
                        $articlesQuery->whereBetween('price', [$value[0], $value[1]]);
                    }
                    break;
                case 'orden':
                    // value: ['campo', 'asc'|'desc']
                    if (is_array($value) && count($value) === 2) {
                        $articlesQuery->orderBy($value[0], $value[1]);
                    }
                    break;
                // Agrega aquí más filtros según tus necesidades
                default:
                    // Filtro genérico por igualdad
                    $articlesQuery->where($key, $value);
            }
        }

        // Orden por defecto si no se ha aplicado orden
        if (!isset($query->filters()['orden'])) {
            $articlesQuery->orderByDesc('created_at');
        }

        $total    = $articlesQuery->count();
        $articles = $articlesQuery->skip($offset)->take($perPage)->get();

        return [
            'category' => (ArticleCategoryResponse::fromCategory($category))->toArray(),
            'articles' => $articles->map(fn($a) => $a->toArray())->toArray(),
            'pagination' => [
                'total'     => $total,
                'page'      => $query->page(),
                'per_page'  => $perPage,
                'last_page' => (int) ceil($total / $perPage),
            ],
        ];
    }
}
