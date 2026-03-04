<template>
    <AdminLayout>
        <Breadcrumbs :items="[{ label: 'Control de Cambios' }]" />

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-9 text-gray-900">Control de Cambios</h1>
                <p class="mt-2 text-sm text-gray-700">Gestiona las solicitudes de cambios en el sistema, desde la propuesta hasta su implementación.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <Link :href="route('admin.change-controls.create')" class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    + Nueva Solicitud
                </Link>
            </div>
        </div>

        <!-- Search -->
        <div class="mt-8">
            <div class="relative rounded-md shadow-sm max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input v-model="searchForm.search" @input="search" type="text" placeholder="Buscar por título o descripción..." class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
        </div>

        <!-- Table -->
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Título</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estado</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Solicitante</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Fecha</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="item in changeControls.data" :key="item.id">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                    {{ item.title }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span :class="getStatusBadgeClass(item.status)" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                        {{ getStatusLabel(item.status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ item.requester?.name || 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ new Date(item.created_at).toLocaleDateString() }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <div class="flex justify-end gap-2">
                                        <Link :href="route('admin.change-controls.edit', item.id)" 
                                              class="text-gray-400 hover:text-blue-600 transition-colors"
                                              title="Editar">
                                            <PencilSquareIcon class="h-5 w-5" />
                                        </Link>

                                        <Link :href="route('admin.change-controls.destroy', item.id)" 
                                              method="delete" 
                                              as="button" 
                                              class="text-gray-400 hover:text-red-600 transition-colors"
                                              title="Eliminar">
                                            <TrashIcon class="h-5 w-5" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <Pagination :data="changeControls" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    changeControls: Object,
    filters: Object,
});

const searchForm = reactive({
    search: props.filters.search || '',
});

const search = () => {
    router.get(route('admin.change-controls.index'), { search: searchForm.search }, {
        preserveState: true,
        replace: true,
    });
};

const getStatusLabel = (status) => {
    const labels = {
        draft: 'Borrador',
        pending: 'Pendiente',
        approved: 'Aprobado',
        implemented: 'Implementado',
    };
    return labels[status] || status;
};

const getStatusBadgeClass = (status) => {
    const classes = {
        draft: 'bg-gray-100 text-gray-800',
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-blue-100 text-blue-800',
        implemented: 'bg-green-100 text-green-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>
