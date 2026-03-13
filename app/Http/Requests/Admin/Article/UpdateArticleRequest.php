<?php

namespace App\Http\Requests\Admin\Article;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_category_id' => 'nullable|exists:article_categories,id',
            'status' => 'required|in:draft,published,scheduled,pending_review',
            'images' => 'nullable|array',
            'localizations' => 'required|array|min:1',

            'localizations.*.market_id' => 'required|integer|exists:markets,id',
            'localizations.*.language_id' => 'required|integer|exists:languages,id',
            'localizations.*.title' => 'required|string|max:255',
            'localizations.*.slug' => 'nullable|string|max:255',
            'localizations.*.excerpt' => 'nullable|string',
            'localizations.*.description' => 'nullable|string',
            // BlockEditor sends nested rows/columns/blocks arrays.
            'localizations.*.content' => 'nullable|array',
            'localizations.*.seo_metadata' => 'nullable|array',
            'localizations.*.seo_metadata.title' => 'nullable|string|max:255',
            'localizations.*.seo_metadata.description' => 'nullable|string',
        ];
    }
}