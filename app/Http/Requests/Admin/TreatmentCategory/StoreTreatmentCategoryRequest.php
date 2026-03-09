<?php

namespace App\Http\Requests\Admin\TreatmentCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'slug.en' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.es' => 'nullable|string',
            'description.en' => 'nullable|string',
            'active' => 'boolean',
            'sort_order' => 'integer',

            'name.*' => 'nullable|string',
            'slug.*' => 'nullable|string',
            'description.*' => 'nullable|string',
        ];
    }
}
