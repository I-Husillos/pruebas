<?php

namespace Src\Web\ContentHandler\Infrastructure;

use Src\Web\ContentHandler\Domain\ContentHandlerInterface;
use App\Models\Page;
use Inertia\Inertia;

class PageHandler implements ContentHandlerInterface
{
    /**
     * Renderiza una página usando Inertia.
     * @param Page $entity
     * @param array $params
     * @return \Inertia\Response
     */
    public function handle($entity, array $params = [])
    {
        // Aquí puedes adaptar los datos según lo que tu frontend requiera
        return Inertia::render('Page/Show', [
            'page' => $entity,
            'params' => $params,
        ]);
    }
}
