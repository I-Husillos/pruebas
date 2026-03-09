<template>
    <ResourceListTable
        title="Productos"
        description="Listado completo del catálogo de Termosalud, incluyendo equipos faciales, corporales y médico-estéticos."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.products.create')"
        edit-route-name="admin.products.edit"
        :columns="columns"
        search-placeholder="Buscar por nombre..."
        resource-name="producto"
        resource-name-plural="productos"
        create-button-text="Nuevo Producto"
    >
        <!-- Custom Name (Multilingual) -->
        <template #cell-name="{ item }">
            {{ item.name?.es || item.name?.en || 'Sin nombre' }}
        </template>

        <!-- Custom Category Name (Multilingual) -->
        <template #cell-category_name="{ item }">
            {{ item.category_name?.es || item.category_name?.en || 'Sin categoría' }}
        </template>

        <!-- Custom Status Badge -->
        <template #cell-published="{ value }">
            <StatusBadge :value="value" />
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';

const { props } = usePage();
const { apiToken, apiUrl } = props;

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'category_name', label: 'Categoría' },
    { key: 'published', label: 'Estado' },
];
</script>
