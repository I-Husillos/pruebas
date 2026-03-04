<template>
  <AdminLayout>
    <Breadcrumbs :items="[{ label: 'Categorías de Tratamientos' }]" />

    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold leading-9 text-gray-900">Categorías de Tratamientos</h1>
        <p class="mt-2 text-sm text-gray-700">Gestión de categorías para clasificar artículos.</p>
      </div>
      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <Link :href="route('admin.treatment-categories.create')" class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
          + Añadir Categoría
        </Link>
      </div>
    </div>

    <!-- Table -->
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
              <tr>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Nombre</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Slug</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Orden</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estado</th>
                <th class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                  <span class="sr-only">Acciones</span>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="category in categories.data" :key="category.id">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                  {{ category.name?.es || category.name?.en || 'Sin nombre' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ category.slug?.es || category.slug?.en }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ category.sort_order }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <span :class="category.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                    {{ category.active ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                  <div class="flex justify-end gap-2">
                    <Link :href="route('admin.treatment-categories.edit', category.id)" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                      <PencilSquareIcon class="h-5 w-5" />
                    </Link>
                    <Link :href="route('admin.treatment-categories.destroy', category.id)" method="delete" as="button" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                      <TrashIcon class="h-5 w-5" />
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Pagination v-if="categories.last_page > 1" :paginator="categories" />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

defineProps({
  categories: Object,
  search: String,
});
</script>
