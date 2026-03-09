<?php

declare(strict_types=1);

namespace Termosalud\Web\Article\Application\Update;

use Termosalud\Web\Article\Domain\ContentArticle;
use Termosalud\Web\Article\Domain\ContentArticleRepository;

final class ContentArticleUpdater
{
    public function __construct(private readonly ContentArticleRepository $repository) {}

    public function __invoke(
        ?int $id,
        string $type,
        array $title,
        array $slug,
        ?array $excerpt,
        ?array $content,
        ?string $author,
        bool $published,
        ?int $categoryId,
        ?\DateTimeImmutable $publishedAt
    ): void
    {
        $contentArticle = new ContentArticle(
            $id,
            $type,
            $title,
            $slug,
            $excerpt,
            $content,
            $author,
            $published,
            $categoryId,
            $publishedAt
        );
        $this->repository->save($contentArticle);
    }
}
