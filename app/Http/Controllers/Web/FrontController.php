<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Admin\BaseController;
use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryBus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Termosalud\Web\Page\Application\Find\FindPageBySlugQuery;

final class FrontController extends BaseController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly DomainSlugResolver $slugResolver,
        private readonly \Src\Web\ContentHandler\Infrastructure\ContentHandlerFactory $handlerFactory
    ) {}

    public function pages(Request $request): Response
    {
        $markets = $request->attributes->get('resolvedMarket');
        $languages = $request->attributes->get('resolvedLanguage');

        $markets = DB::table('markets')->where('active', true)->orderBy('priority')->get();
        $languages = DB::table('languages')->where('active', true)->get();

        $languageId = (int) ($languages->firstWhere('code', $langCode)->id ?? 0);
        $marketId = (int) ($markets->firstWhere('code', $marketCode)->id ?? 0);

        // Cambia 'home' por el slug de la página concreta que quieras mostrar
        $slug = 'home';

        /** @var \Termosalud\Web\Page\Application\PageResponse|null $pageResponse */
        $pageResponse = $this->queryBus->ask(new FindPageBySlugQuery(
            $slug,
            $languageId,
            $marketId
        ));
        

        $page = $pageResponse ? $pageResponse->toArray() : null;

        
        if (!$page) {
            $page = [
                'localizations' => [],
                'status' => 'not-found',
                'message' => 'Página no encontrada o no configurada.'
            ];
        }

        return Inertia::render('/', [
            'pages' => $page,
        ]);
    }

    // Página dinámica por slug (solo páginas, paso a paso)
    public function __invoke(Request $request, string $market, string $language, string $slug, ?string $extra = null): Response
    {
        // 1. Obtener market/language de la request (middleware los fija en atributos)
        $market = $request->attributes->get('resolvedMarket');
        $language = $request->attributes->get('resolvedLanguage');


        // Usar el SlugResolver desacoplado (devuelve ResolvedContent)
        $resolved = $this->slugResolver->resolve($slug, $market->id(), $language->id());

        // 3. Parsear $extra a parámetros si aplica (para filtros, paginación, etc.)
        $params = $this->parseExtraSegments($extra ?? '');

        // 4. Renderizar usando la factory (por ahora solo soporta Page)
        return $this->handlerFactory->handle($resolved->entity, $params);
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
