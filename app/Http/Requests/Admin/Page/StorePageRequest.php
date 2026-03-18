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
            'localizations.*.title'               => 'required|string|max:255',
            'localizations.*.excerpt'             => 'nullable|string|max:500',
            'localizations.*.description'         => 'nullable|string',
            'localizations.*.content'             => 'nullable|array',
            'localizations.*.seo_metadata'        => 'required|array',
            'localizations.*.seo_metadata.title'  => 'required|string|max:255',
            'localizations.*.seo_metadata.description' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'localizations.*.title.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.title.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.description.required' => 'Este campo es obligatorio.',
        ];
    }
}
