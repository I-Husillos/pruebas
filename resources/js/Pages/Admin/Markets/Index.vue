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
        order-by="name"
        order="asc"
    >
        <template #filters="{ fetchData, setDynamicFilter, clearDynamicFilters }">
            <div class="flex items-end gap-4">
                <div class="flex flex-col">
                    <label for="market-status-filter" class="sr-only">Estado</label>
                    <select
                        id="market-status-filter"
                        v-model="activeFilter"
                        @change="applyActiveFilter(setDynamicFilter, fetchData)"
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

