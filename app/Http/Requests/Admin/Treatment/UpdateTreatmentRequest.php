<?php

namespace App\Http\Requests\Admin\Treatment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentRequest extends FormRequest
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
            'name.en' => 'required|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'slug.en' => 'required|string|max:255',
            'description' => 'nullable|array',
            'published' => 'boolean',
            'available_markets' => 'nullable|array',
            'sort_order' => 'integer',
            'category_id' => 'nullable|exists:treatment_categories,id',
            'blocks_json' => 'nullable|array',

            // Allow generic language keys
            'name.*' => 'nullable|string',
            'slug.*' => 'nullable|string',
        ];
    }
}
