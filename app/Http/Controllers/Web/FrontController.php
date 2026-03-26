<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Termosalud\Web\Page\Application\Find\FindPageBySlugQuery;
use Termosalud\Web\Article\Application\Find\FindArticleBySlugQuery;
use Termosalud\Web\ArticleCategory\Application\Find\FindArticleCategoryBySlugQuery;
use Termosalud\Web\Product\Application\Find\FindProductBySlugQuery;
use Termosalud\Web\Treatment\Application\Find\FindTreatmentBySlugQuery;

final class FrontController extends BaseController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {}

    public function __invoke(Request $request, string $market, string $language, string $slug, ?string $extra = null): Response
    {
        $markets = $request->attributes->get('resolvedMarket');
        $languages = $request->attributes->get('resolvedLanguage');

        $extraParams = $extra ? $this->parseExtraSegments($extra) : [];
        $currentPage = isset($extraParams['pagina']) ? (int) $extraParams['pagina'] : 1;

        $contentType = null;
        $content = null;

        
        $page = $this->queryBus->ask(new FindPageBySlugQuery($slug, $languages->id(), $markets->id()));
        if ($page) {
            $contentType = 'page';
            $content = $page->toArray();
        }

        if (!$content) {
            $article = $this->queryBus->ask(new FindArticleBySlugQuery($slug, $languages->id(), $markets->id()));
            if ($article) {
                $contentType = 'article';
                $content = $article->toArray();
            }
        }

        if (!$content) {
            $product = $this->queryBus->ask(new FindProductBySlugQuery($slug, $languages->id(), $markets->id()));
            if ($product) {
                $contentType = 'product';
                $content = $product->toArray();
            }
        }

        if (!$content) {
            $treatment = $this->queryBus->ask(new FindTreatmentBySlugQuery($slug, $languages->id(), $markets->id()));
            if ($treatment) {
                $contentType = 'treatment';
                $content = $treatment->toArray();
            }
        }

        if (!$content) {
            $articleCategory = $this->queryBus->ask(
                new FindArticleCategoryBySlugQuery(
                    slug:       $slug,
                    languageId: $languages->id(),
                    page:       $currentPage,
                    perPage:    12,
                    filters:    $extraParams,
                )
            );
            if ($articleCategory) {
                $contentType = 'article_category';
                $content     = $articleCategory;
            }
        }

        // Response que deberíamos obtener de cada iteración
        FrontofficeResponse FrontofficeResolver 
        $foPage = new \stdClass();
        $foPage->component = match($contentType) {
            'page' => 'Pages/Show',
            'article' => 'Articles/Show',
            'article_category' => 'Articles/Category',
            'product' => 'Products/Show',
            'treatment' => 'Treatments/Show',
            default => 'Errors/NotFound',
        };
        
        $foPage->props = [
            $contentType ?? 'content' => $content,
            'market' => $market,
            'lang' => $language,
        ];

        return Inertia::render($foPage->component, $foPage->props);
    }

    /**
     * Parsea el segmento extra de la URL en parámetros clave-valor.
     * Ejemplo: "precio-100-500_pagina-2" ['precio' ['100', '500'], 'pagina' '2']
     */
    private function parseExtraSegments(string $extra): array
    {
        if (empty($extra)) {
            return [];
        }
        $params = [];
        $segments = explode('_', $extra);
        foreach ($segments as $segment) {
            $parts = explode('-', $segment);
            if (count($parts) > 1) {
                $key = array_shift($parts);
                $params[$key] = count($parts) > 1 ? $parts : $parts[0];
            } elseif (count($parts) === 1) {
                $params[$parts[0]] = true;
            }
        }
        return $params;
    }
}
