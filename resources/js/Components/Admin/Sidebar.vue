<template>
    <div
        class="flex flex-col w-64 bg-gray-800 h-screen fixed left-0 top-0 overflow-y-auto transition-all duration-300 z-50">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 bg-gray-900 shadow-md">
            <h1 class="text-white text-xl font-bold tracking-wider">TERMOSALUD</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-2">
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

            <div>
                <button @click="isBlogOpen = !isBlogOpen"
                    class="w-full group flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none">
                    <div class="flex items-center">
                        <DocumentTextIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                        Blog
                    </div>
                    <ChevronDownIcon
                        :class="[isBlogOpen ? 'rotate-180' : '', 'h-5 w-5 transform transition-transform duration-200']" />
                </button>
                <div v-show="isBlogOpen" class="mt-1 space-y-1 pl-11">
                    <Link :href="route('admin.articles.index')"
                        :class="[route().current('admin.articles.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Artículos
                    </Link>
                    <Link :href="route('admin.article-categories.index')"
                        :class="[route().current('admin.article-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías de Artículos
                    </Link>
                </div>
            </div>

            <div>
                <button @click="isProductsOpen = !isProductsOpen"
                    class="w-full group flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none">
                    <div class="flex items-center">
                        <CubeIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                        Productos
                    </div>
                    <ChevronDownIcon
                        :class="[isProductsOpen ? 'rotate-180' : '', 'h-5 w-5 transform transition-transform duration-200']" />
                </button>
                <div v-show="isProductsOpen" class="mt-1 space-y-1 pl-11">
                    <Link :href="route('admin.products.index')"
                        :class="[route().current('admin.products.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Listado de Productos
                    </Link>
                    <Link :href="route('admin.product-categories.index')"
                        :class="[route().current('admin.product-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías
                    </Link>
                </div>
            </div>

            <div>
                <button @click="isTreatmentsOpen = !isTreatmentsOpen"
                    class="w-full group flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none">
                    <div class="flex items-center">
                        <BeakerIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                        Tratamientos
                    </div>
                    <ChevronDownIcon
                        :class="[isTreatmentsOpen ? 'rotate-180' : '', 'h-5 w-5 transform transition-transform duration-200']" />
                </button>
                <div v-show="isTreatmentsOpen" class="mt-1 space-y-1 pl-11">
                    <Link :href="route('admin.treatments.index')"
                        :class="[route().current('admin.treatments.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Listado de Tratamientos
                    </Link>
                    <Link :href="route('admin.treatment-categories.index')"
                        :class="[route().current('admin.treatment-categories.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 pr-2 text-sm font-medium rounded-md']">
                        Categorías
                    </Link>
                </div>
            </div>

            <div>
                <button @click="isManagementOpen = !isManagementOpen"
                    class="w-full group flex items-center justify-between px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none">
                    <div class="flex items-center">
                        <Cog6ToothIcon class="mr-3 h-6 w-6 flex-shrink-0" aria-hidden="true" />
                        Gestión General
                    </div>
                    <ChevronDownIcon
                        :class="[isManagementOpen ? 'rotate-180' : '', 'h-5 w-5 transform transition-transform duration-200']" />
                </button>
                <div v-show="isManagementOpen" class="mt-1 space-y-1 pl-11">
                    <Link :href="route('admin.markets.index')"
                        :class="[route().current('admin.markets.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Mercados</Link>
                    <Link :href="route('admin.languages.index')"
                        :class="[route().current('admin.languages.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Idiomas</Link>
                    <Link :href="route('admin.pages.index')"
                        :class="[route().current('admin.pages.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Páginas</Link>
                    <div class="border-t border-gray-700 my-2"></div>
                    <Link :href="route('admin.users.index')"
                        :class="[route().current('admin.users.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Usuarios</Link>
                    <Link :href="route('admin.forms.index')"
                        :class="[route().current('admin.forms.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Formularios</Link>
                    <Link :href="route('admin.media.index')"
                        :class="[route().current('admin.media.*') ? 'text-white' : 'text-gray-400 hover:text-white', 'block py-2 text-sm font-medium']">
                        Medios</Link>
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
import { HomeIcon, ChevronDownIcon, ClipboardDocumentCheckIcon, DocumentTextIcon, CubeIcon, BeakerIcon, Cog6ToothIcon } from '@heroicons/vue/24/outline';


// Estados independientes para cada menú
const isBlogOpen = ref(route().current('admin.articles.*') || route().current('admin.article-categories.*'));
const isProductsOpen = ref(route().current('admin.products.*') || route().current('admin.product-categories.*'));
const isTreatmentsOpen = ref(route().current('admin.treatments.*') || route().current('admin.treatment-categories.*'));
const isManagementOpen = ref(false);
</script>
