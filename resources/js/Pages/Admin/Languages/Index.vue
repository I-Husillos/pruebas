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
        :per-page="5"
    >
        <!-- Custom Filters -->
        <template #filters="{ fetchData, search, setDynamicFilter, clearDynamicFilters }">
            <div class="flex items-end gap-4">
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">
                        Estado
                    </label>
                    <select
                        id="statusFilter"
                        v-model="statusFilter"
                        @change="applyStatusFilter(setDynamicFilter, fetchData)"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <button
                    @click="resetFilters(fetchData, search, clearDynamicFilters)"
                    class="text-sm text-indigo-600 hover:text-indigo-500 font-medium pb-2.5"
                >
                    Limpiar filtros
                </button>
            </div>
        </template>

        <!-- Custom Name with Native Name -->
        <template #cell-name="{ item }">
            {{ item.name }} ({{ item.native_name }})
        </template>

        <!-- Custom Direction (uppercase) -->
        <template #cell-direction="{ value }">
            <span class="uppercase">{{ value }}</span>
        </template>

        <!-- Custom Status Badge -->
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
