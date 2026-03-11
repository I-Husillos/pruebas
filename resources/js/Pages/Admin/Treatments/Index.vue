<template>
    <ResourceListTable
        title="Tratamientos"
        description="Listado de tratamientos y protocolos"
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.treatments.create')"
        edit-route-name="admin.treatments.edit"
        :columns="columns"
        search-placeholder="Buscar tratamientos..."
        resource-name="tratamiento"
        resource-name-plural="tratamientos"
        create-button-text="Crear Tratamiento"
        :enable-row-reorder="true"
        :reorder-api-url="reorderApiUrl"
    >
        <!-- Custom Name (Multilingual) -->
        <template #cell-name="{ item }">
            {{ item.name?.es || item.name?.en || 'Sin nombre' }}
        </template>

        <!-- Custom Status Badge -->
        <template #cell-published="{ value }">
            <StatusBadge :value="value" true-text="Publicado" false-text="Borrador" />
        </template>

        <!-- Custom Actions with View Link -->
        <template #actions="{ item, edit, remove }">
            <div class="flex justify-end gap-2">
                <!-- View -->
                <a :href="`/es/es/treatments/${item.slug?.es || item.slug?.en || 'unknown'}`" 
                   target="_blank"
                   class="text-gray-400 hover:text-indigo-600 transition-colors"
                   title="Ver en web">
                    <EyeIcon class="h-5 w-5" />
                </a>

                <!-- Edit -->
                <button @click="edit" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                    <PencilSquareIcon class="h-5 w-5" />
                </button>

                <!-- Delete -->
                <button @click="remove" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                    <TrashIcon class="h-5 w-5" />
                </button>
            </div>
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';
import { EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const { props } = usePage();
const { apiToken, apiUrl } = props;
const reorderApiUrl = `${apiUrl}/reorder`;

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'sort_order', label: 'Orden' },
    { key: 'published', label: 'Estado' },
];
</script>
