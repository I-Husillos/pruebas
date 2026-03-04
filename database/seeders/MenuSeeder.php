<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Main Navigation Menu
        $mainMenu = Menu::firstOrCreate(
            ['key' => 'main-navigation'],
            [
                'name' => ['es' => 'Navegación Principal', 'en' => 'Main Navigation'],
                'description' => ['es' => 'Menú principal del sitio', 'en' => 'Main site menu'],
                'active' => true,
            ]
        );

        $mainMenuItems = [
            [
                'label' => ['es' => 'Inicio', 'en' => 'Home'],
                'route_name' => 'home',
                'sort_order' => 1,
            ],
            [
                'label' => ['es' => 'Productos', 'en' => 'Products'],
                'route_name' => 'products.index.es',
                'sort_order' => 2,
            ],
            [
                'label' => ['es' => 'Tratamientos', 'en' => 'Treatments'],
                'route_name' => 'treatments.index.es',
                'sort_order' => 3,
            ],
            [
                'label' => ['es' => 'Blog', 'en' => 'Blog'],
                'route_name' => 'articles.index.es',
                'sort_order' => 4,
            ],
        ];

        foreach ($mainMenuItems as $item) {
            MenuItem::firstOrCreate(
                [
                    'menu_id' => $mainMenu->id,
                    'label' => $item['label'],
                ],
                array_merge($item, [
                    'menu_id' => $mainMenu->id,
                    'active' => true,
                    'target' => '_self',
                ])
            );
        }

        // Footer Menu
        $footerMenu = Menu::firstOrCreate(
            ['key' => 'footer-menu'],
            [
                'name' => ['es' => 'Menú del Pie', 'en' => 'Footer Menu'],
                'description' => ['es' => 'Menú del pie de página', 'en' => 'Footer menu'],
                'active' => true,
            ]
        );

        $footerMenuItems = [
            [
                'label' => ['es' => 'Sobre Nosotros', 'en' => 'About Us'],
                'url' => '#',
                'sort_order' => 1,
            ],
            [
                'label' => ['es' => 'Contacto', 'en' => 'Contact'],
                'url' => '#',
                'sort_order' => 2,
            ],
            [
                'label' => ['es' => 'Política de Privacidad', 'en' => 'Privacy Policy'],
                'url' => '#',
                'sort_order' => 3,
            ],
        ];

        foreach ($footerMenuItems as $item) {
            MenuItem::firstOrCreate(
                [
                    'menu_id' => $footerMenu->id,
                    'label' => $item['label'],
                ],
                array_merge($item, [
                    'menu_id' => $footerMenu->id,
                    'active' => true,
                    'target' => '_self',
                ])
            );
        }
    }
}
