<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        // Article Categories
        $articleCategories = [
            [
                'name' => ['es' => 'Noticias', 'en' => 'News'],
                'slug' => ['es' => 'noticias', 'en' => 'news'],
                'description' => ['es' => 'Últimas noticias', 'en' => 'Latest news'],
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => ['es' => 'Guías', 'en' => 'Guides'],
                'slug' => ['es' => 'guias', 'en' => 'guides'],
                'description' => ['es' => 'Guías y tutoriales', 'en' => 'Guides and tutorials'],
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => ['es' => 'Investigación', 'en' => 'Research'],
                'slug' => ['es' => 'investigacion', 'en' => 'research'],
                'description' => ['es' => 'Artículos de investigación', 'en' => 'Research articles'],
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => ['es' => 'Prensa', 'en' => 'Press'],
                'slug' => ['es' => 'prensa', 'en' => 'press'],
                'description' => ['es' => 'Prensa', 'en' => 'Press'],
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => ['es' => 'Eventos', 'en' => 'Events'],
                'slug' => ['es' => 'eventos', 'en' => 'events'],
                'description' => ['es' => 'Eventos', 'en' => 'Events'],
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => ['es' => 'Testimonios', 'en' => 'Testimonials'],
                'slug' => ['es' => 'testimonios', 'en' => 'testimonials'],
                'description' => ['es' => 'Testimonios', 'en' => 'Testimonials'],
                'active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($articleCategories as $category) {
            ArticleCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
