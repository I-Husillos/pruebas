<?php

namespace Src\Web\ContentHandler\Infrastructure;

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
    public function handle($entity, array $params = [])
    {
        if ($entity instanceof Page) {
            // Renderiza la página directamente usando Inertia
            return Inertia::render('Page/Show', [
                'page' => $entity,
                'params' => $params,
            ]);
        }
        throw new \InvalidArgumentException('No handler disponible para la entidad dada.');
    }
}
