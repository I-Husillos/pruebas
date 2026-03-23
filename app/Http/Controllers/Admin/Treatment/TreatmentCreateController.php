<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Treatment;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Form;
use Inertia\Response;
use App\Models\TreatmentCategory;
use App\Models\Market;
use App\Models\Language;

final class TreatmentCreateController extends BaseController
{
    public function __invoke(): Response
    {
        $categories = TreatmentCategory::query()
            ->with('translations')
            ->orderBy('id')
            ->get()
            ->map(fn (TreatmentCategory $category) => [
                'id'    => $category->id,
                'title' => $category->translations->first()?->title
                        ?? "Categoría #{$category->id}",
            ]);

        $languages = Language::where('active', 1)->get(['id', 'code', 'name', 'native_name'])->keyBy('code');

        $markets = Market::query()
            ->where('active', true)
            ->orderBy('priority')
            ->get()
            ->map(function (Market $market) use ($languages) {
                $enabledLanguages = collect($market->enabled_languages ?? [])
                    ->map(function (string $code) use ($languages, $market) {
                        $language = $languages->get($code);
                        if (! $language) return null;
                        return [
                            'id'         => $language->id,
                            'code'       => $language->code,
                            'name'       => $language->native_name ?: $language->name,
                            'is_default' => $language->code === $market->default_language,
                        ];
                    })
                    ->filter()
                    ->sortByDesc(fn (array $l) => $l['is_default'])
                    ->values()
                    ->all();

                return [
                    'id'        => $market->id,
                    'code'      => $market->code,
                    'name'      => $market->name,
                    'region'    => $market->region,
                    'languages' => $enabledLanguages,
                ];
            })
            ->filter(fn (array $m) => ! empty($m['languages']))
            ->values();

        $forms = Form::query()
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'key']);

        return $this->render('Admin/Treatments/Create', [
            'categories' => $categories,
            'markets'    => $markets,
            'forms'      => $forms,
        ]);
    }
}
