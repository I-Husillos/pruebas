<?php

namespace App\Http\Requests\Admin\ArticleCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'                             => 'required|string|in:active,inactive',
            'order'                              => 'integer|min:0',
            'translations'                       => 'required|array|min:1',
            'translations.*.language_id'         => 'required|integer|exists:languages,id',
            'translations.*.title'               => 'required|string|max:255',
            'translations.*.slug'                => 'required|string|max:255',
            'translations.*.description'         => 'required|string|max:1000',
            'translations.*.seo_metadata'        => 'required|array',
            'translations.*.seo_metadata.title'  => 'required|string|max:255',
            'translations.*.seo_metadata.description' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'translations.*.title.required'                      => 'Este campo es obligatorio.',
            'translations.*.slug.required'                       => 'Este campo es obligatorio.',
            'translations.*.description.required'                => 'Este campo es obligatorio.',
            'translations.*.seo_metadata.required'               => 'Este campo es obligatorio.',
            'translations.*.seo_metadata.title.required'         => 'Este campo es obligatorio.',
            'translations.*.seo_metadata.description.required'   => 'Este campo es obligatorio.',
        ];
    }
}