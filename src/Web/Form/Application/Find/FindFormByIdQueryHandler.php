<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Application\FormResponse;

final class FindFormByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(FindFormByIdQuery $query): ?FormResponse
    {
        $form = $this->repository->findById($query->id());
        $response = $form ? FormResponse::fromForm($form) : null;

        return $response;
    }
}
