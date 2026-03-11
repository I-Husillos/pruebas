<template>
    <ResourceListTable
        title="Idiomas"
        description="Gestión de idiomas para la localización de contenidos multilingües de Termosalud."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.languages.create')"
        edit-route-name="admin.languages.edit"
        :columns="columns"
        :search-fields="searchFields"
        search-placeholder="Buscar por código, nombre, nombre nativo, dirección o idioma de respaldo..."
        resource-name="idioma"
        resource-name-plural="idiomas"
        create-button-text="Añadir Idioma"
        order-by="name"
        order="asc"
    >
        <template #filters="{ fetchData, setDynamicFilter, clearDynamicFilters }">
            <div class="flex items-end gap-4">
                <div class="flex flex-col">
                    <label for="statusFilter" class="sr-only">Estado</label>
                    <select
                        id="statusFilter"
                        v-model="statusFilter"
                        @change="applyStatusFilter(setDynamicFilter, fetchData)"
                        class="block w-40 h-10 rounded-md border border-gray-300 text-sm shadow-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    >
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

                <button
                    @click="resetFilters(fetchData, clearDynamicFilters)"
                    class="text-sm text-indigo-600 hover:text-indigo-500 font-medium h-10 flex items-center"
                >
                    Limpiar filtros
                </button>
            </div>
        </template>

        <template #cell-name="{ item }">
            {{ item.name }} ({{ item.native_name }})
        </template>

        <template #cell-direction="{ value }">
            <span class="uppercase">{{ value }}</span>
        </template>

        <template #cell-active="{ value }">
            <StatusBadge :value="value" />
        </template>
    </ResourceListTable>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';

const { props } = usePage();
const { apiToken, apiUrl } = props;

// Define searchable fields for wildcard multi-field search
const searchFields = ['code', 'name', 'native_name', 'direction', 'fallback_language'];

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'code', label: 'Código' },
    { key: 'direction', label: 'Dirección' },
    { key: 'active', label: 'Estado' },
];

// Dynamic filter state
const statusFilter = ref('');

// Apply status filter
const applyStatusFilter = (setDynamicFilter, fetchData) => {
    if (statusFilter.value === '') {
        setDynamicFilter({ field: 'active', operator: null, value: null });
    } else {
        setDynamicFilter({
            field: 'active',
            operator: 'EQUAL',
            value: statusFilter.value
        });
    }
    fetchData();
};

// Reset all filters
const resetFilters = (fetchData, search, clearDynamicFilters) => {
    statusFilter.value = '';
    search.value = '';
    clearDynamicFilters();
    fetchData();
};
</script>
