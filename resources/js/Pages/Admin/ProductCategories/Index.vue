<template>
    <ResourceListTable
        title="Categorías de Productos"
        description="Gestión de categorías para clasificar productos."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.product-categories.create')"
        edit-route-name="admin.product-categories.edit"
        :columns="columns"
        order-by="order"
        order="asc"
        search-placeholder="Buscar categorías..."
        resource-name="categoría"
        resource-name-plural="categorías"
        create-button-text="Añadir Categoría"
        :enable-row-reorder="true"
        :reorder-api-url="reorderApiUrl"
        :search-fields="searchFields"
    >
        <!-- Custom Title (Translations) -->
        <template #cell-title="{ item }">
            {{ getTranslationValue(item, 'title') || 'Sin título' }}
        </template>

        <!-- Custom Slug (Translations) -->
        <template #cell-slug="{ item }">
            {{ getTranslationValue(item, 'slug') || 'Sin slug' }}
        </template>

        <!-- Custom Status Badge -->
        <template #cell-status="{ value }">
            <StatusBadge :value="value === 'active'" />
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';
import { getTranslationValue } from '@/utils/translationUtils';

const { props } = usePage();
const { apiToken, apiUrl } = props;
const reorderApiUrl = `${apiUrl}/reorder`;

const searchFields = ['title', 'slug'];

const columns = [
    { key: 'title', label: 'Título' },
    { key: 'slug', label: 'Slug' },
    { key: 'order', label: 'Orden' },
    { key: 'status', label: 'Estado' },
];
</script>

