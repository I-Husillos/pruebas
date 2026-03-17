<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
            'localizations.*.slug'                => 'required|string|max:255|distinct',
            'localizations.*.title'               => 'nullable|string|max:255',
            'localizations.*.excerpt'             => 'nullable|string|max:500',
            'localizations.*.description'         => 'nullable|string',
            'localizations.*.content'             => 'nullable|array',
            'localizations.*.seo_metadata'        => 'nullable|array',
            'localizations.*.seo_metadata.title'  => 'nullable|string|max:255',
            'localizations.*.seo_metadata.description' => 'nullable|string|max:500',
        ];
    }
}
