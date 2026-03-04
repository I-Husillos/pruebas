<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'es',
                'name' => 'Español',
                'native_name' => 'Español',
                'direction' => 'ltr',
                'active' => true,
                'fallback_language' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'en',
                'name' => 'Inglés',
                'native_name' => 'English',
                'direction' => 'ltr',
                'active' => true,
                'fallback_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'fr',
                'name' => 'Francés',
                'native_name' => 'Français',
                'direction' => 'ltr',
                'active' => false,
                'fallback_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'de',
                'name' => 'Alemán',
                'native_name' => 'Deutsch',
                'direction' => 'ltr',
                'active' => false,
                'fallback_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'it',
                'name' => 'Italiano',
                'native_name' => 'Italiano',
                'direction' => 'ltr',
                'active' => false,
                'fallback_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'pt',
                'name' => 'Portugués',
                'native_name' => 'Português',
                'direction' => 'ltr',
                'active' => false,
                'fallback_language' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('languages')->insert($languages);
    }
}
