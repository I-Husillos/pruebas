<?php

declare(strict_types=1);

namespace Termosalud\Web\Page\Domain;

use Dba\DddSkeleton\Shared\Domain\Aggregate\AggregateRoot;

final class Page extends AggregateRoot
{
    public function __construct(
        private readonly ?int $id,
        private string        $status,
        private array         $localizations,
        private ?string       $createdAt,
        private ?string       $updatedAt,
        private ?string       $deletedAt,
    ) {}

    public static function create(string $status, array $localizations): self
    {
        return new self(null, $status, $localizations, null, null, null);
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            $data['id']            ?? null,
            $data['status']        ?? 'draft',
            $data['localizations'] ?? [],
            $data['created_at']    ?? null,
            $data['updated_at']    ?? null,
            $data['deleted_at']    ?? null,
        );
    }

    public function id(): ?int             { return $this->id; }
    public function status(): string       { return $this->status; }
    public function localizations(): array { return $this->localizations; }
    public function createdAt(): ?string   { return $this->createdAt; }
    public function updatedAt(): ?string   { return $this->updatedAt; }
    public function deletedAt(): ?string   { return $this->deletedAt; }

    public function toPrimitives(): array
    {
        return [
            'id'            => $this->id,
            'status'        => $this->status,
            'localizations' => $this->localizations,
            'created_at'    => $this->createdAt,
            'updated_at'    => $this->updatedAt,
            'deleted_at'    => $this->deletedAt,
        ];
    }
}
