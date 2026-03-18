<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Language;
use Inertia\Response;

final class ArticleCategoryCreateController extends BaseController
{
    public function __invoke(): Response
    {
        $languages = Language::where('active', true)
            ->orderBy('code')
            ->get(['id', 'code', 'name', 'native_name'])
            ->map(fn($l) => [
                'id'   => $l->id,
                'code' => $l->code,
                'name' => $l->native_name ?: $l->name,
            ])
            ->values();
        
        $translations = $languages->map(fn($language) => [
            'language_id' => $language['id'],
            'title' => '',
            'slug' => '',
            'description' => '',
            'seo_metadata' => [
                'title' => '',
                'description' => '',
            ],
        ]);

        return $this->render('Admin/ArticleCategories/Create', [
            'languages' => $languages,
            'translations' => $translations,
        ]);
    }
}
