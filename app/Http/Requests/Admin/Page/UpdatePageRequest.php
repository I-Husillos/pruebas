<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'       => 'required|in:draft,published,scheduled,pending_review',

            'localizations'                       => 'required|array|min:1',
            'localizations.*.language_id'         => 'required|integer|exists:languages,id',
            'localizations.*.market_id'           => 'required|integer|exists:markets,id',
            'localizations.*.slug'                => [
                'nullable', 'string', 'max:255', 'distinct',
                Rule::unique('page_localizations', 'slug')
                    ->where(fn ($q) => $q->whereNot('page_id', (int) $this->route('id'))),
            ],
            'localizations.*.title'               => 'nullable|string|max:255',
            'localizations.*.excerpt'             => 'nullable|string',
            'localizations.*.description'         => 'nullable|string',
            'localizations.*.content'             => 'nullable|array',
            'localizations.*.seo_metadata'        => 'nullable|array',
            'localizations.*.seo_metadata.title'  => 'nullable|string|max:255',
            'localizations.*.seo_metadata.description' => 'nullable|string',
        ];
    }
}
