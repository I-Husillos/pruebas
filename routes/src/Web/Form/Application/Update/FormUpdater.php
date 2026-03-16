<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Update;

use Termosalud\Web\Form\Domain\Form;
use Termosalud\Web\Form\Domain\FormRepository;

final class FormUpdater
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(
        int $id,
        string $name,
        string $key,
        string $recipientEmail,
        array $fields,
        bool $isActive
    ): void {
        $form = new Form(
            $id,
            $name,
            $key,
            $recipientEmail,
            $fields,
            $isActive
        );

        $this->repository->save($form);
    }
}
