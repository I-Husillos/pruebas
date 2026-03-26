<?php

declare(strict_types=1);

namespace App\Http\Middleware\Front;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Termosalud\Web\Language\Domain\LanguageRepository;

final class LoadLanguage
{
    public function __construct(private readonly LanguageRepository $languageRepository) {}

    public function handle(Request $request, Closure $next): Response
    {
        $languageCode = (string) $request->route('lang');

        if ($languageCode === '') {
            abort(404);
        }

        $language = $this->languageRepository->findByCode(strtolower($languageCode));

        $allLanguages = $this->languageRepository->findAllActive();

        if ($language === null || $language->id() === null) {
            abort(404);
        }

        $request->attributes->set('resolvedLanguage', $language);
        $request->attributes->set('allLanguages', $allLanguages);

        return $next($request);
    }
}
