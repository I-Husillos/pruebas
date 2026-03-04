<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Gestión de Widgets por Zona</h1>

      <div class="space-y-8">
        <div v-for="zone in zones" :key="zone.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ zone.name?.es || zone.name?.en }}</h3>
              <p class="text-sm text-gray-500">{{ zone.key }}</p>
            </div>
            <Link :href="route('admin.widgets.create', { zone_id: zone.id })" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
              + Añadir Widget
            </Link>
          </div>

          <div v-if="zone.widgets && zone.widgets.length > 0" class="space-y-3">
            <div v-for="widget in zone.widgets" :key="widget.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">{{ widget.type }}</span>
                  <span class="text-sm font-medium text-gray-900">{{ widget.title?.es || widget.title?.en || 'Sin título' }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Orden: {{ widget.sort_order }}</p>
              </div>
              <div class="flex items-center gap-2">
                <span :class="widget.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex rounded-full px-2 py-1 text-xs font-semibold">
                  {{ widget.active ? 'Activo' : 'Inactivo' }}
                </span>
                <Link :href="route('admin.widgets.edit', widget.id)" class="text-gray-400 hover:text-blue-600">
                  <PencilSquareIcon class="h-5 w-5" />
                </Link>
                <Link :href="route('admin.widgets.destroy', widget.id)" method="delete" as="button" class="text-gray-400 hover:text-red-600">
                  <TrashIcon class="h-5 w-5" />
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6 text-gray-500 text-sm">
            No hay widgets en esta zona
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

defineProps({
  zones: Array,
});

const breadcrumbItems = [
  { label: 'Widgets' }
];
</script>
