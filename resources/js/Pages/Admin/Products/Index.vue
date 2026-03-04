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
        <!-- Custom Status Badge -->
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
    { key: 'category.name', label: 'Categoría' },
    { key: 'active', label: 'Estado' },
];
</script>
