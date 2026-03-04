<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold leading-9 text-gray-900">Items del Menú: {{ menu.name?.es }}</h1>
        <p class="mt-2 text-sm text-gray-700">Gestiona los enlaces del menú. Puedes crear items anidados seleccionando un item padre.</p>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <Link :href="route('admin.menus.items.create', menu.id)" class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
          + Añadir Item
        </Link>
      </div>
    </div>

    <!-- Menu Items Tree -->
    <div class="mt-8 space-y-4">
      <div v-if="items && items.length > 0">
        <MenuItemList 
          :items="items" 
          @change="saveOrder"
          @delete="deleteItem" 
        />
      </div>
      
      <!-- Empty State -->
      <div v-else class="text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay items en este menú</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza agregando el primer item al menú.</p>
        <div class="mt-6">
          <Link :href="route('admin.menus.items.create', menu.id)" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Añadir primer item
          </Link>
        </div>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between">
      <Link :href="route('admin.menus.index')" class="text-sm text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Volver a Menús
      </Link>
      
      <div class="text-sm text-gray-500">
        Total: {{ items.length }} {{ items.length === 1 ? 'item' : 'items' }}
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link, router } from '@inertiajs/vue3';
import MenuItemList from './MenuItemList.vue';

const props = defineProps({
  menu: Object,
  items: Array,
});

const breadcrumbItems = [
  { label: 'Menús', link: route('admin.menus.index') },
  { label: props.menu.name?.es || 'Menú', link: route('admin.menus.edit', props.menu.id) },
  { label: 'Items' }
];

const saveOrder = () => {
    // The items array is mutated directly by vuedraggable
    router.post(route('admin.menu-items.reorder'), { 
        items: props.items 
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show toast
        }
    });
};

const deleteItem = (id) => {
    if (confirm('¿Estás seguro de que quieres eliminar este item?')) {
        router.delete(route('admin.menu-items.destroy', id));
    }
};
</script>
