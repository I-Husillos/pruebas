<?php

namespace Termosalud\Web\Form\Domain;

class Form
{
    public $id;

    public $name;

    public $key;

    public $recipientEmail;

    public $fields;

    public $isActive;

    public $submissionsCount;

    public function __construct(
        ?int $id,
        string $name,
        string $key,
        ?string $recipientEmail,
        array $fields, // JSON array of field definitions
        bool $isActive,
        int $submissionsCount = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->key = $key;
        $this->recipientEmail = $recipientEmail;
        $this->fields = $fields;
        $this->isActive = $isActive;
        $this->submissionsCount = $submissionsCount;
    }
}
