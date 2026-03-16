<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\Response;
use Termosalud\Web\Form\Domain\Form;

final class FormResponse implements Response
{
    private int $id;
    private string $name;
    private string $key;
    private ?string $recipientEmail;
    private array $fields;
    private bool $isActive;
    private int $submissionsCount;

    public function __construct(
        int $id,
        string $name,
        string $key,
        ?string $recipientEmail,
        array $fields,
        bool $isActive,
        int $submissionsCount
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->key = $key;
        $this->recipientEmail = $recipientEmail;
        $this->fields = $fields;
        $this->isActive = $isActive;
        $this->submissionsCount = $submissionsCount;
    }

    public static function fromForm(Form $form): self
    {
        return new self(
            $form->id,
            $form->name,
            $form->key,
            $form->recipientEmail,
            $form->fields,
            $form->isActive,
            $form->submissionsCount
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'key' => $this->key,
            'recipient_email' => $this->recipientEmail,
            'fields' => $this->fields,
            'is_active' => $this->isActive,
            'submissions_count' => $this->submissionsCount,
        ];
    }
}
