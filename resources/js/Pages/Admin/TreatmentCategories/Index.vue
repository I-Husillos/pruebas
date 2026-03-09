<template>
    <ResourceListTable
        title="Categorías de Tratamientos"
        description="Gestión de categorías para clasificar tratamientos."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.treatment-categories.create')"
        edit-route-name="admin.treatment-categories.edit"
        :columns="columns"
        search-placeholder="Buscar categorías..."
        resource-name="categoría"
        resource-name-plural="categorías"
        create-button-text="Añadir Categoría"
    >
        <template #cell-name="{ item }">
            {{ item.name?.es || item.name?.en || 'Sin nombre' }}
        </template>

        <template #cell-slug="{ item }">
            {{ item.slug?.es || item.slug?.en }}
        </template>

        <template #cell-active="{ value }">
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
    { key: 'slug', label: 'Slug' },
    { key: 'sort_order', label: 'Orden' },
    { key: 'active', label: 'Estado' },
];
</script>
