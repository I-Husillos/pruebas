<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Submit;

use Dba\DddSkeleton\Shared\Domain\Bus\Command\CommandHandler;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Domain\FormSubmission;

final class SubmitFormCommandHandler implements CommandHandler
{
    public function __construct(private readonly FormSubmitter $submitter) {}

    public function __invoke(SubmitFormCommand $command): void
    {
        $this->submitter->__invoke(
            $command->formKey(),
            $command->data(),
            $command->ip(),
            $command->userAgent()
        );
    }
}
