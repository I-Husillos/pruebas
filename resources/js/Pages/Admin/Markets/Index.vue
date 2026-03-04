<template>
    <ResourceListTable
        title="Mercados"
        description="Configuración de regiones geográficas y mercados internacionales de Termosalud."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.markets.create')"
        edit-route-name="admin.markets.edit"
        :columns="columns"
        :search-fields="searchFields"
        search-placeholder="Buscar mercados..."
        resource-name="mercado"
        resource-name-plural="mercados"
        create-button-text="Configurar Mercado"
    >
        <!-- Custom Filters -->
        <template #filters="{ fetchData, setDynamicFilter, clearDynamicFilters }">
            <div class="flex items-end gap-3">
                <div>
                    <label for="market-status-filter" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select
                        id="market-status-filter"
                        v-model="activeFilter"
                        @change="applyActiveFilter(setDynamicFilter, fetchData)"
                        class="block w-40 rounded-md border-gray-300 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

                <button @click="resetFilters(fetchData, clearDynamicFilters)" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium pb-2.5">
                    Limpiar filtros
                </button>
            </div>
        </template>

        <!-- Custom Status Badge -->
        <template #cell-active="{ value }">
            <StatusBadge :value="value" />
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';

const { props } = usePage();
const { apiToken, apiUrl } = props;
const activeFilter = ref('');

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'code', label: 'Código' },
    { key: 'region', label: 'Región' },
    { key: 'active', label: 'Estado' },
];

const searchFields = ['name', 'code', 'region'];

const applyActiveFilter = (setDynamicFilter, fetchData) => {
    setDynamicFilter({
        field: 'active',
        operator: 'EQUAL',
        value: activeFilter.value,
    });
    fetchData();
};

const resetFilters = (fetchData, clearDynamicFilters) => {
    activeFilter.value = '';
    clearDynamicFilters();
    fetchData();
};
</script>

