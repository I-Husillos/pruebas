<?php

namespace Database\Seeders;

use App\Models\WidgetZone;
use Illuminate\Database\Seeder;

class WidgetZoneSeeder extends Seeder
{
    public function run()
    {
        $zones = [
            [
                'key' => 'header',
                'name' => ['es' => 'Cabecera', 'en' => 'Header'],
                'description' => ['es' => 'Zona superior de la página', 'en' => 'Top area of the page'],
                'active' => true,
            ],
            [
                'key' => 'footer',
                'name' => ['es' => 'Pie de página', 'en' => 'Footer'],
                'description' => ['es' => 'Zona inferior de la página', 'en' => 'Bottom area of the page'],
                'active' => true,
            ],
            [
                'key' => 'sidebar-left',
                'name' => ['es' => 'Barra lateral izquierda', 'en' => 'Left Sidebar'],
                'description' => ['es' => 'Barra lateral izquierda', 'en' => 'Left sidebar area'],
                'active' => true,
            ],
            [
                'key' => 'sidebar-right',
                'name' => ['es' => 'Barra lateral derecha', 'en' => 'Right Sidebar'],
                'description' => ['es' => 'Barra lateral derecha', 'en' => 'Right sidebar area'],
                'active' => true,
            ],
            [
                'key' => 'before-content',
                'name' => ['es' => 'Antes del contenido', 'en' => 'Before Content'],
                'description' => ['es' => 'Zona antes del contenido principal', 'en' => 'Area before main content'],
                'active' => true,
            ],
            [
                'key' => 'after-content',
                'name' => ['es' => 'Después del contenido', 'en' => 'After Content'],
                'description' => ['es' => 'Zona después del contenido principal', 'en' => 'Area after main content'],
                'active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            WidgetZone::firstOrCreate(
                ['key' => $zone['key']],
                $zone
            );
        }
    }
}
