<?php

declare(strict_types=1);

namespace Termosalud\Web\Market\Application\Create;

use Termosalud\Web\Market\Domain\Market;
use Termosalud\Web\Market\Domain\MarketRepository;

final class MarketCreator
{
    public function __construct(private readonly MarketRepository $repository) {}

    public function __invoke(
        string $code,
        string $name,
        string $region,
        string $defaultLanguage,
        array $enabledLanguages,
        bool $active,
        int $priority
    ): void {
        $market = new Market(
            null,
            $code,
            $name,
            $region,
            $defaultLanguage,
            $enabledLanguages,
            $active,
            $priority
        );

        $this->repository->save($market);
    }
}