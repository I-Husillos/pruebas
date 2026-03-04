<template>
    <ResourceListTable
        title="Páginas"
        description="Páginas de aterrizaje y contenido específico por mercado e idioma"
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.pages.create')"
        edit-route-name="admin.pages.edit"
        :columns="columns"
        search-placeholder="Buscar páginas..."
        resource-name="página"
        resource-name-plural="páginas"
        create-button-text="Crear Página"
    >
        <!-- Custom Market Code (uppercase) -->
        <template #cell-marketCode="{ value }">
            <span class="uppercase">{{ value }}</span>
        </template>

        <!-- Custom Language Code (uppercase) -->
        <template #cell-languageCode="{ value }">
            <span class="uppercase">{{ value }}</span>
        </template>

        <!-- Custom Status Badge -->
        <template #cell-isActive="{ value }">
            <StatusBadge :value="value" />
        </template>

        <!-- Custom Actions with View Link -->
        <template #actions="{ item, edit, remove }">
            <div class="flex justify-end gap-2">
                <!-- View -->
                <a :href="`/${item.marketCode}/${item.languageCode}/pages/${item.slug}`" 
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

const columns = [
    { key: 'slug', label: 'Slug' },
    { key: 'marketCode', label: 'Mercado' },
    { key: 'languageCode', label: 'Idioma' },
    { key: 'isActive', label: 'Estado' },
];
</script>
