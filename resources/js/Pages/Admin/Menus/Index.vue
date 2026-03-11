<template>
  <AdminLayout>
    <Breadcrumbs :items="[{ label: 'Menús' }]" />

    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold leading-9 text-gray-900">Menús</h1>
        <p class="mt-2 text-sm text-gray-700">Gestión de menús de navegación del sitio. Haz clic en un menú para ver sus enlaces.</p>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <Link :href="route('admin.menus.create')" class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
          + Añadir Menú
        </Link>
      </div>
    </div>

    <!-- Menu Cards -->
    <div class="mt-8 space-y-4">
      <div v-for="menu in menus.data" :key="menu.id" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Menu Header -->
        <div class="p-6">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center gap-3">
                <h3 class="text-lg font-semibold text-gray-900">
                  {{ menu.name?.es || menu.name?.en || 'Sin nombre' }}
                </h3>
                <span :class="menu.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">
                  {{ menu.active ? 'Activo' : 'Inactivo' }}
                </span>
                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                  {{ menu.all_items_count }} {{ menu.all_items_count === 1 ? 'enlace' : 'enlaces' }}
                </span>
              </div>
              <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ menu.key }}</span>
                <span v-if="menu.description?.es || menu.description?.en" class="text-gray-600">
                  {{ menu.description?.es || menu.description?.en }}
                </span>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2 ml-4">
              <button @click="toggleMenu(menu.id)" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" :title="expandedMenus.includes(menu.id) ? 'Ocultar enlaces' : 'Ver enlaces'">
                <svg class="h-5 w-5 transition-transform" :class="expandedMenus.includes(menu.id) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <Link :href="route('admin.menus.items.index', menu.id)" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Gestionar items">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </Link>
              <Link :href="route('admin.menus.edit', menu.id)" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                <PencilSquareIcon class="h-5 w-5" />
              </Link>
              <!-- <Link :href="route('admin.menus.destroy', menu.id)" method="delete" as="button" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                <TrashIcon class="h-5 w-5" />
              </Link> -->
            </div>
          </div>
        </div>

        <!-- Expandable Menu Items Preview -->
        <div v-if="expandedMenus.includes(menu.id)" class="border-t border-gray-200 bg-gray-50 px-6 py-4">
          <div v-if="menu.items && menu.items.length > 0" class="space-y-2">
            <div v-for="item in menu.items" :key="item.id" class="bg-white rounded-lg p-3 border border-gray-200">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-2">
                    <i v-if="item.icon" :class="item.icon" class="text-gray-500"></i>
                    <span class="font-medium text-gray-900">{{ item.label?.es || item.label?.en }}</span>
                    <span v-if="item.target === '_blank'" class="text-xs text-gray-400">(nueva ventana)</span>
                  </div>
                  <div class="mt-1 text-xs text-gray-500 flex items-center gap-2">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    <span class="font-mono">{{ item.url || `route: ${item.route_name}` || '#' }}</span>
                  </div>
                </div>
                <span :class="item.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium">
                  {{ item.active ? 'Activo' : 'Inactivo' }}
                </span>
              </div>
              
              <!-- Nested Children -->
              <div v-if="item.children && item.children.length > 0" class="mt-3 ml-6 space-y-2 border-l-2 border-gray-200 pl-4">
                <div v-for="child in item.children" :key="child.id" class="flex items-start justify-between py-1">
                  <div class="flex-1">
                    <div class="flex items-center gap-2">
                      <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                      <i v-if="child.icon" :class="child.icon" class="text-gray-400 text-sm"></i>
                      <span class="text-sm text-gray-700">{{ child.label?.es || child.label?.en }}</span>
                    </div>
                    <div class="mt-0.5 ml-5 text-xs text-gray-400 font-mono">
                      {{ child.url || `route: ${child.route_name}` || '#' }}
                    </div>
                  </div>
                  <span :class="child.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="inline-flex rounded-full px-1.5 py-0.5 text-xs font-medium ml-2">
                    {{ child.active ? '✓' : '✗' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6 text-gray-500 text-sm">
            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <p>No hay enlaces en este menú</p>
            <Link :href="route('admin.menus.items.create', menu.id)" class="mt-2 inline-flex items-center text-indigo-600 hover:text-indigo-500 text-sm font-medium">
              + Añadir primer enlace
            </Link>
          </div>
        </div>
      </div>
    </div>

    <Pagination v-if="menus.last_page > 1" :paginator="menus" class="mt-6" />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

defineProps({
  menus: Object,
  search: String,
});

const expandedMenus = ref([]);

const toggleMenu = (menuId) => {
  const index = expandedMenus.value.indexOf(menuId);
  if (index > -1) {
    expandedMenus.value.splice(index, 1);
  } else {
    expandedMenus.value.push(menuId);
  }
};
</script>
