<?php

namespace Src\Web\Shared\Application\Front;

use App\Models\Page;
use Inertia\Inertia;

class ContentHandlerFactory
{
    /**
     * Devuelve una respuesta renderizada directamente para Page.
     * @param mixed $entity
     * @param array $params
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function handle($entity, array $params = [], $markets = [], $languages = [], $market, $language)
    {
        //dd($entity, $params, $markets, $languages);
        // Renderiza la página directamente usando Inertia
        return Inertia::render('Home', [
            'page' => $entity,
            'params' => $params,
            'markets' => $markets,
            'languages' => $languages,
            'market' => $market,
            'lang' => $language,
        ]);
        //throw new \InvalidArgumentException('No handler disponible para la entidad dada.');
    }
}
