<?php

namespace App\Http\Requests\Admin\ArticleCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'                       => 'required|string|in:active,inactive',
            'order'                        => 'integer|min:0',
            'translations'                 => 'required|array|min:1',
            'translations.*.language_id'   => 'required|integer|exists:languages,id',
            'translations.*.title'         => 'required|string|max:255',
            'translations.*.description'   => 'nullable|string',
            'translations.*.slug'          => 'required|string|max:255',
            'translations.*.seo_metadata'  => 'nullable|array',
        ];
    }
}