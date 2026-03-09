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
            'type' => 'required|in:blog,news,press',
            'title' => 'required|array',
            'title.es' => 'required|string|max:255',
            'slug' => 'required|array',
            'excerpt' => 'nullable|array',
            'content' => 'nullable|array',
            'author' => 'nullable|string|max:255',
            'published' => 'boolean',
            'published_at' => 'nullable|date',
            'category_id' => 'nullable|exists:article_categories,id',
            'featured_image' => 'nullable|array',

            'title.*' => 'nullable|string',
            'slug.*' => 'nullable|string',
            'excerpt.*' => 'nullable|string',
            'content.*' => 'nullable|string',
        ];
    }
}