<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'market_code' => 'required|string|max:2',
            'language_code' => 'required|string|max:2',
            'slug' => 'required|string|max:255',
            'is_active' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'blocks_json' => 'nullable|array',
        ];
    }
}
