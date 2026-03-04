<?php

declare(strict_types=1);

namespace Termosalud\Web\Form\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Form\Domain\FormRepository;
use Termosalud\Web\Form\Application\FormResponse;

final class FindFormByKeyQueryHandler implements QueryHandler
{
    public function __construct(private readonly FormRepository $repository) {}

    public function __invoke(FindFormByKeyQuery $query): ?FormResponse
    {
        $form = $this->repository->findByKey($query->key());
        return $form ? FormResponse::fromForm($form) : null;
    }
}
