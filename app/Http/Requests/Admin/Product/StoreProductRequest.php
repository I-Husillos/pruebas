<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'product_category_id'              => 'nullable|exists:product_categories,id',
                'code'                             => 'required|string|max:255|unique:products,code',
                'status'                           => 'required|in:draft,published,scheduled,pending_review',
                'images'                           => 'nullable|array',
                'related_treatments'               => 'nullable|array',
                'order'                            => 'nullable|integer',

                'localizations'                    => 'required|array|min:1',
                'localizations.*.market_id'        => 'required|integer|exists:markets,id',
                'localizations.*.language_id'      => 'required|integer|exists:languages,id',
                'localizations.*.title'            => 'required|string|max:255',
                'localizations.*.slug'             => 'required|string|max:255',
                'localizations.*.excerpt'          => 'nullable|string|max:500',
                'localizations.*.description'      => 'nullable|string',
                'localizations.*.content'          => 'nullable|array',
                'localizations.*.seo_metadata'     => 'nullable|array',
                'localizations.*.seo_metadata.title'       => 'nullable|string|max:255',
                'localizations.*.seo_metadata.description' => 'nullable|string|max:500',
        ];
    }
}
