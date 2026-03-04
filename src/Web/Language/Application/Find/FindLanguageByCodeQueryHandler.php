<?php

declare(strict_types=1);

namespace Termosalud\Web\Language\Application\Find;

use Dba\DddSkeleton\Shared\Domain\Bus\Query\QueryHandler;
use Termosalud\Web\Language\Application\LanguageResponse;
use Termosalud\Web\Language\Domain\LanguageRepository;

final class FindLanguageByCodeQueryHandler implements QueryHandler
{
    public function __construct(private readonly LanguageRepository $repository) {}

    public function __invoke(FindLanguageByCodeQuery $query): ?LanguageResponse
    {
        $Language = $this->repository->findByCode($query->code());

        return $Language ? LanguageResponse::fromLanguage($Language) : null;
    }
}
