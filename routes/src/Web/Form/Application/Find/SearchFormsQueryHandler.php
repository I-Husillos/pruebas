<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Application\FormResponse;
use Termosalud\Web\Form\Application\FormsResponse;

final class SearchFormsQueryHandler implements QueryHandler
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(SearchFormsQuery $query): FormsResponse
    {
        $forms = $this->repository->search($query->search());
        $responses = array_map(fn($form) => FormResponse::fromForm($form), $forms);
        return new FormsResponse(...$responses);
    }
}
