<?php

declare(strict_types=1);

namespace App\Http\Middleware\Front;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Termosalud\Web\Market\Domain\MarketRepository;

final class LoadMarket
{
    public function __construct(private readonly MarketRepository $marketRepository) {}

    public function handle(Request $request, Closure $next): Response
    {
        $marketCode = (string) $request->route('market');

        if ($marketCode === '') {
            abort(404);
        }

        $allMarkets = $this->marketRepository->findAllActive();

        $market = $this->marketRepository->findByCode(strtoupper($marketCode));

        if ($market === null || $market->id() === null) {
            abort(404);
        }

        $request->attributes->set('resolvedMarket', $market);
        $request->attributes->set('allMarkets', $allMarkets);

        return $next($request);
    }
}
