<?php

namespace Src\Web\ContentHandler\Domain;

interface ContentHandlerInterface
{
    /**
     * Ejecuta la lógica de presentación/renderizado para la entidad.
     * @param mixed $entity
     * @param array $params Parámetros extra (filtros, paginación, etc.)
     * @return mixed
     */
    public function handle($entity, array $params = []);
}
