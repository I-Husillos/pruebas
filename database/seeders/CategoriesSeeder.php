<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $languageIds = DB::table('languages')
            ->whereIn('code', ['es', 'en'])
            ->pluck('id', 'code');

        if (!isset($languageIds['es']) || !isset($languageIds['en'])) {
            return;
        }

        $articleCategories = [
            [
                'name' => ['es' => 'Noticias', 'en' => 'News'],
                'slug' => ['es' => 'noticias', 'en' => 'news'],
                'description' => ['es' => 'Últimas noticias', 'en' => 'Latest news'],
                'order' => 1,
            ],
            [
                'name' => ['es' => 'Guías', 'en' => 'Guides'],
                'slug' => ['es' => 'guias', 'en' => 'guides'],
                'description' => ['es' => 'Guías y tutoriales', 'en' => 'Guides and tutorials'],
                'order' => 2,
            ],
            [
                'name' => ['es' => 'Investigación', 'en' => 'Research'],
                'slug' => ['es' => 'investigacion', 'en' => 'research'],
                'description' => ['es' => 'Artículos de investigación', 'en' => 'Research articles'],
                'order' => 3,
            ],
            [
                'name' => ['es' => 'Prensa', 'en' => 'Press'],
                'slug' => ['es' => 'prensa', 'en' => 'press'],
                'description' => ['es' => 'Prensa', 'en' => 'Press'],
                'order' => 4,
            ],
            [
                'name' => ['es' => 'Eventos', 'en' => 'Events'],
                'slug' => ['es' => 'eventos', 'en' => 'events'],
                'description' => ['es' => 'Eventos', 'en' => 'Events'],
                'order' => 5,
            ],
            [
                'name' => ['es' => 'Testimonios', 'en' => 'Testimonials'],
                'slug' => ['es' => 'testimonios', 'en' => 'testimonials'],
                'description' => ['es' => 'Testimonios', 'en' => 'Testimonials'],
                'order' => 6,
            ],
        ];

        foreach ($articleCategories as $category) {
            $categoryId = DB::table('article_category_translations')
                ->where('slug', $category['slug']['es'])
                ->value('article_category_id');

            if ($categoryId) {
                DB::table('article_categories')
                    ->where('id', $categoryId)
                    ->update([
                        'status' => 'active',
                        'order' => $category['order'],
                        'updated_at' => now(),
                    ]);
            } else {
                $categoryId = DB::table('article_categories')->insertGetId([
                    'status' => 'active',
                    'order' => $category['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('article_category_translations')->updateOrInsert(
                [
                    'article_category_id' => $categoryId,
                    'language_id' => $languageIds['es'],
                ],
                [
                    'title' => $category['name']['es'],
                    'description' => $category['description']['es'],
                    'slug' => $category['slug']['es'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::table('article_category_translations')->updateOrInsert(
                [
                    'article_category_id' => $categoryId,
                    'language_id' => $languageIds['en'],
                ],
                [
                    'title' => $category['name']['en'],
                    'description' => $category['description']['en'],
                    'slug' => $category['slug']['en'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $productCategories = [
            [
                'name' => ['es' => 'Cosmética', 'en' => 'Cosmetics'],
                'slug' => ['es' => 'cosmetica', 'en' => 'cosmetics'],
                'description' => ['es' => 'Productos cosméticos', 'en' => 'Cosmetic products'],
                'order' => 1,
            ],
            [
                'name' => ['es' => 'Dispositivos Médicos', 'en' => 'Medical Devices'],
                'slug' => ['es' => 'dispositivos-medicos', 'en' => 'medical-devices'],
                'description' => ['es' => 'Dispositivos para uso profesional', 'en' => 'Devices for professional use'],
                'order' => 2,
            ],
            [
                'name' => ['es' => 'Suplementos', 'en' => 'Supplements'],
                'slug' => ['es' => 'suplementos', 'en' => 'supplements'],
                'description' => ['es' => 'Suplementos de apoyo terapéutico', 'en' => 'Therapeutic support supplements'],
                'order' => 3,
            ],
        ];

        foreach ($productCategories as $category) {
            $categoryId = DB::table('product_category_translations')
                ->where('slug', $category['slug']['es'])
                ->value('product_category_id');

            if ($categoryId) {
                DB::table('product_categories')
                    ->where('id', $categoryId)
                    ->update([
                        'status' => 'active',
                        'order' => $category['order'],
                        'updated_at' => now(),
                    ]);
            } else {
                $categoryId = DB::table('product_categories')->insertGetId([
                    'status' => 'active',
                    'order' => $category['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('product_category_translations')->updateOrInsert(
                [
                    'product_category_id' => $categoryId,
                    'language_id' => $languageIds['es'],
                ],
                [
                    'title' => $category['name']['es'],
                    'description' => $category['description']['es'],
                    'slug' => $category['slug']['es'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::table('product_category_translations')->updateOrInsert(
                [
                    'product_category_id' => $categoryId,
                    'language_id' => $languageIds['en'],
                ],
                [
                    'title' => $category['name']['en'],
                    'description' => $category['description']['en'],
                    'slug' => $category['slug']['en'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $treatmentCategories = [
            [
                'name' => ['es' => 'Faciales', 'en' => 'Facial Treatments'],
                'slug' => ['es' => 'faciales', 'en' => 'facial-treatments'],
                'description' => ['es' => 'Tratamientos faciales avanzados', 'en' => 'Advanced facial procedures'],
                'order' => 1,
            ],
            [
                'name' => ['es' => 'Corporales', 'en' => 'Body Treatments'],
                'slug' => ['es' => 'corporales', 'en' => 'body-treatments'],
                'description' => ['es' => 'Tratamientos para remodelación y cuidado corporal', 'en' => 'Body contouring and care treatments'],
                'order' => 2,
            ],
            [
                'name' => ['es' => 'Capilares', 'en' => 'Hair Treatments'],
                'slug' => ['es' => 'capilares', 'en' => 'hair-treatments'],
                'description' => ['es' => 'Tratamientos para salud capilar', 'en' => 'Treatments for hair health'],
                'order' => 3,
            ],
        ];

        foreach ($treatmentCategories as $category) {
            $categoryId = DB::table('treatment_category_translations')
                ->where('slug', $category['slug']['es'])
                ->value('treatment_category_id');

            if ($categoryId) {
                DB::table('treatment_categories')
                    ->where('id', $categoryId)
                    ->update([
                        'status' => 'active',
                        'order' => $category['order'],
                        'updated_at' => now(),
                    ]);
            } else {
                $categoryId = DB::table('treatment_categories')->insertGetId([
                    'status' => 'active',
                    'order' => $category['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('treatment_category_translations')->updateOrInsert(
                [
                    'treatment_category_id' => $categoryId,
                    'language_id' => $languageIds['es'],
                ],
                [
                    'title' => $category['name']['es'],
                    'description' => $category['description']['es'],
                    'slug' => $category['slug']['es'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::table('treatment_category_translations')->updateOrInsert(
                [
                    'treatment_category_id' => $categoryId,
                    'language_id' => $languageIds['en'],
                ],
                [
                    'title' => $category['name']['en'],
                    'description' => $category['description']['en'],
                    'slug' => $category['slug']['en'],
                    'seo_metadata' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
