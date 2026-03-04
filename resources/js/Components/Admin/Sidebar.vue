<template>
    <div class="flex flex-col w-64 bg-gray-800 h-screen fixed left-0 top-0 overflow-y-auto transition-all duration-300 z-50">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-gray-900 shadow-md">
            <h1 class="text-white text-xl font-bold tracking-wider">TERMOSALUD</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-2">
            <!-- Dashboard -->
            <Link :href="route('admin.dashboard')" 
                  :class="[route().current('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                <HomeIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                Dashboard
            </Link>

            <Link :href="route('admin.change-controls.index')" 
                  :class="[route().current('admin.change-controls.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                <ClipboardDocumentCheckIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                Control de Cambios
            </Link>

            <!-- Collapsible Section: Gestión -->
            <div>
                <button @click="toggleManagement" 
                        class="w-full group flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none">
                    <div class="flex items-center">
                        <FolderIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                        Gestión
                    </div>
                    <ChevronDownIcon :class="[isManagementOpen ? 'rotate-180' : '', 'h-5 w-5 transform transition-transform duration-200']" />
                </button>
                
                <!-- Submenu -->
                <div v-show="isManagementOpen" class="mt-1 space-y-1 pl-11">
                    <Link :href="route('admin.products.index')" 
                          :class="[route().current('admin.products.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Productos
                    </Link>
                    <Link :href="route('admin.treatments.index')" 
                          :class="[route().current('admin.treatments.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Tratamientos
                    </Link>
                    <Link :href="route('admin.articles.index')" 
                          :class="[route().current('admin.articles.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Artículos
                    </Link>
                    <Link :href="route('admin.markets.index')" 
                          :class="[route().current('admin.markets.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Mercados
                    </Link>
                    <Link :href="route('admin.languages.index')" 
                          :class="[route().current('admin.languages.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Idiomas
                    </Link>
                    <Link :href="route('admin.pages.index')" 
                          :class="[route().current('admin.pages.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Páginas
                    </Link>
                    <div class="border-t border-gray-700 my-2"></div>
                    <Link :href="route('admin.users.index')" 
                          :class="[route().current('admin.users.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Usuarios
                    </Link>
                    <Link :href="route('admin.forms.index')" 
                          :class="[route().current('admin.forms.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Formularios
                    </Link>
                    <Link :href="route('admin.media.index')" 
                          :class="[route().current('admin.media.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Galería de Medios
                    </Link>
                    <div class="border-t border-gray-700 my-2"></div>
                    <Link :href="route('admin.article-categories.index')" 
                          :class="[route().current('admin.article-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías de Artículos
                    </Link>
                    <Link :href="route('admin.product-categories.index')" 
                          :class="[route().current('admin.product-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías de Productos
                    </Link>
                    <Link :href="route('admin.treatment-categories.index')" 
                          :class="[route().current('admin.treatment-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías de Tratamientos
                    </Link>
                    <div class="border-t border-gray-700 my-2"></div>
                    <Link :href="route('admin.menus.index')" 
                          :class="[route().current('admin.menus.*') || route().current('menu-items.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Menús
                    </Link>
                    <Link :href="route('admin.widgets.index')" 
                          :class="[route().current('admin.widgets.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Widgets
                    </Link>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { HomeIcon, FolderIcon, ChevronDownIcon, ClipboardDocumentCheckIcon } from '@heroicons/vue/24/outline';

const isManagementOpen = ref(true);

const toggleManagement = () => {
    isManagementOpen.value = !isManagementOpen.value;
};
</script>
