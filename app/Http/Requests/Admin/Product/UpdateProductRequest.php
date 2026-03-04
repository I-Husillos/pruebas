<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product') ?? $this->route('id'); // Get ID from route

        return [
            'code' => 'required|string|max:255|unique:products,code,'.$id,
            'name' => 'required|array',
            'name.es' => 'required|string|max:255',
            'slug' => 'required|array',
            'slug.es' => 'required|string|max:255',
            'description' => 'nullable|array',
            'published' => 'boolean',
            'available_markets' => 'nullable|array',
            'category_id' => 'nullable|exists:product_categories,id',
            'blocks_json' => 'nullable|array',

            // Allow other languages
            'name.*' => 'nullable|string',
            'slug.*' => 'nullable|string',
        ];
    }
}
