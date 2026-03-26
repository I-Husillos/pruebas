<?php

namespace Src\Web\Shared\Domain\Slug;

class ResolvedContent
{
    public const TYPE_PAGE = 'page';
    // En el futuro: public const TYPE_ARTICLE = 'article', etc.

    public function __construct(
        public readonly string $type,
        public readonly object $entity
    ) {}
}
