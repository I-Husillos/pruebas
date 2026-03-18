<?php

namespace App\Http\Requests\Admin\Article;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Campos de la tabla maestra `articles`
            'article_category_id' => 'nullable|exists:article_categories,id',
            'status' => 'required|in:draft,published,scheduled,pending_review',
            'images' => 'nullable|array',

            // Array de localizaciones - cada elemento es una fila en article_localizations
            'localizations' => 'required|array|min:1',
            'localizations.*.market_id' => 'required|integer|exists:markets,id',
            'localizations.*.language_id' => 'required|integer|exists:languages,id',
            'localizations.*.title' => 'required|string|max:255',
            'localizations.*.slug' => 'required|string|max:255',
            'localizations.*.excerpt' => 'nullable|string|max:500',

            // content es el array JSON del BlockEditor: rows → columns → blocks
            'localizations.*.content' => 'nullable|array',
            'localizations.*.seo_metadata' => 'required|array',
            'localizations.*.seo_metadata.title' => 'required|string|max:255',
            'localizations.*.seo_metadata.description' => 'required|string|max:500',
        ];
    }

        public function messages(): array
    {
        return [
            'code.required' => 'El código es obligatorio.',
            'localizations.*.title.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.title.required' => 'Este campo es obligatorio.',
            'localizations.*.seo_metadata.description.required' => 'Este campo es obligatorio.',
        ];
    }
}