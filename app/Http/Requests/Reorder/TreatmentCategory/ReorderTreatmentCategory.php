<?php

namespace App\Http\Requests\Reorder\TreatmentCategory;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTreatmentCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:treatment_categories,id',
            'items.*.order'  => 'required|integer|min:0',
        ];
    }
}
