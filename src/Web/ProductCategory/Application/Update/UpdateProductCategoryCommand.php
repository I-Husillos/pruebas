<?php

declare(strict_types=1);

namespace Termosalud\Web\ProductCategory\Application\Update;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\Command;

final class UpdateProductCategoryCommand implements Command
{
    /**
     * @param array<int, array{language_id: int, title: string, description: ?string, slug: string, seo_metadata: ?array}> $translations
     */
    public function __construct(
        private readonly int $id,
        private readonly string $status,
        private readonly int $order,
        private readonly array $translations
    ) {}

    public function id(): int         { return $this->id; }
    public function status(): string  { return $this->status; }
    public function order(): int      { return $this->order; }
    public function translations(): array { return $this->translations; }
}
