<template>
    <ResourceListTable
        title="Tratamientos"
        description="Listado completo del catálogo de tratamientos."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.treatments.create')"
        edit-route-name="admin.treatments.edit"
        :columns="columns"
        :search-fields="searchFields"
        search-placeholder="Buscar tratamientos..."
        resource-name="tratamiento"
        resource-name-plural="tratamientos"
        create-button-text="Nuevo Tratamiento"
        order-by="id"
        order="desc"
    >
        <template #cell-name="{ item }">
            {{ getTitle(item) }}
        </template>

        <template #cell-status="{ value }">
            <span
                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                :class="value === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
            >
                {{ value || 'draft' }}
            </span>
        </template>

        <template #cell-created_at="{ value }">
            {{ formatDate(value) }}
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import { formatDate } from '@/utils/formatters';

const { props } = usePage();
const { apiToken, apiUrl } = props;

const searchFields = ['title', 'slug'];

const columns = [
    { key: 'name',       label: 'Nombre' },
    { key: 'status',     label: 'Estado' },
    { key: 'created_at', label: 'Fecha' },
];

const getTitle = (item) => {
    const locs = item?.localizations || [];
    return locs.find((l) => l.title?.trim())?.title || 'Sin nombre';
};
</script>
