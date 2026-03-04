<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Create;

use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class ContentArticleCreator
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke($id, $type, $title, $slug, $excerpt, $content, $author, $published): void
    {
        $contentArticle = new ContentArticle($id, $type, $title, $slug, $excerpt, $content, $author, $published, null);
        
        $this->repository->save($contentArticle);
    }
}
