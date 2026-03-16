<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Create;

use Termosalud\Web\Form\Domain\Form;
use Termosalud\Web\Form\Domain\FormRepository;

final class FormCreator
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(
        string $name,
        string $key,
        string $recipientEmail,
        array $fields,
        bool $isActive
    ): void {
        $form = new Form(
            null,
            $name,
            $key,
            $recipientEmail,
            $fields,
            $isActive
        );

        $this->repository->save($form);
    }
}
