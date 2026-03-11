<template>
    <ResourceListTable
        title="Artículos"
        description="Noticias, blog y comunicados de prensa. Gestiona la comunicación oficial de Termosalud."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.articles.create')"
        edit-route-name="admin.articles.edit"
        :columns="columns"
        search-placeholder="Buscar artículos..."
        resource-name="artículo"
        resource-name-plural="artículos"
        create-button-text="Nuevo Artículo"
        :enable-row-reorder="true"
        :reorder-api-url="reorderApiUrl"
    >
        <!-- Custom Name (Multilingual) -->
        <template #cell-name="{ item }">
            {{ item.title?.es || item.title?.en || 'Sin nombre' }}
        </template>

        <!-- Custom Status Badge -->
        <template #cell-published="{ value }">
            <StatusBadge :value="value" true-text="Publicado" false-text="Inactivo" />
        </template>

        <!-- Custom Date Formatting -->
        <template #cell-created_at="{ value }">
            {{ formatDate(value) }}
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';
import { formatDate } from '@/utils/formatters';

const { props } = usePage();
const { apiToken, apiUrl } = props;
const reorderApiUrl = `${apiUrl}/reorder`;

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'type', label: 'Tipo', format: (value) => value ? value.charAt(0).toUpperCase() + value.slice(1) : 'N/A' },
    { key: 'created_at', label: 'Fecha' },
    { key: 'published', label: 'Estado' },
];
</script>

