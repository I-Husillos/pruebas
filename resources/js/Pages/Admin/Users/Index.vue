<template>
    <ResourceListTable
        title="Usuarios"
        description="Gestión de usuarios y asignación de roles."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.users.create')"
        edit-route-name="admin.users.edit"
        :columns="columns"
        search-placeholder="Buscar por nombre o email..."
        resource-name="usuario"
        resource-name-plural="usuarios"
        create-button-text="Nuevo Usuario"
    >
        <!-- Custom Filters -->
        <template #filters="{ fetchData }">
            <div class="flex items-end">
                <button @click="resetFilters(fetchData)" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium pb-2.5">
                    Limpiar filtros
                </button>
            </div>
        </template>

        <!-- Custom Roles Display -->
        <template #cell-roles="{ value }">
            <span v-for="role in value" :key="role" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-1">
                {{ role }}
            </span>
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
import { formatDate } from '@/utils/formatters';

const { props } = usePage();
const { apiToken, apiUrl } = props;

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'email', label: 'Email' },
    { key: 'roles', label: 'Roles' },
    { key: 'created_at', label: 'Fecha Registro' },
];

const resetFilters = (fetchData) => {
    fetchData();
};
</script>
