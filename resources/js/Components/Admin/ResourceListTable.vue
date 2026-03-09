<template>
    <AdminLayout>
        <Breadcrumbs :items="[{ label: title }]" />

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-9 text-gray-900">{{ title }}</h1>
                <p v-if="description" class="mt-2 text-sm text-gray-700">{{ description }}</p>
            </div>
            <div v-if="createRoute" class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <Link :href="createRoute" class="block rounded-lg bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    + {{ createButtonText }}
                </Link>
            </div>
        </div>

        <!-- Error Alert -->
        <div v-if="error" class="mt-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="relative rounded-md shadow-sm max-w-md flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input 
                    v-model="search" 
                        @input="onSearchInput" 
                    type="text" 
                    :placeholder="searchPlaceholder" 
                    class="block w-full rounded-md border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                />
            </div>

            <!-- Custom Filters Slot -->
                <slot 
                    name="filters"
                    :fetch-data="fetchData"
                    :dynamic-filters="dynamicFilters"
                    :set-dynamic-filters="setDynamicFilters"
                    :add-dynamic-filter="addDynamicFilter"
                    :set-dynamic-filter="setDynamicFilter"
                    :clear-dynamic-filters="clearDynamicFilters"
                ></slot>
        </div>

        <!-- Table -->
        <div class="mt-8 flow-root">
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            <div v-else-if="items.length === 0" class="text-center py-12">
                <p class="text-gray-500">No hay {{ resourceNamePlural }} disponibles</p>
            </div>
            <div v-else class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th 
                                    v-for="column in columns" 
                                    :key="column.key"
                                    :class="column.headerClass || 'px-3 py-3.5 text-left text-sm font-semibold text-gray-900'"
                                    class="first:pl-4 first:pr-3 first:sm:pl-0"
                                >
                                    {{ column.label }}
                                </th>
                                <th v-if="showActions" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="item in items" :key="item.id">
                                <td 
                                    v-for="column in columns" 
                                    :key="column.key"
                                    :class="column.cellClass || 'px-3 py-4 text-sm text-gray-500'"
                                    class="first:pl-4 first:pr-3 first:sm:pl-0 first:font-medium first:text-gray-900 whitespace-nowrap"
                                >
                                    <!-- Custom Cell Slot -->
                                    <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">
                                        <!-- Default Cell Rendering -->
                                        <component 
                                            :is="column.component" 
                                            v-if="column.component"
                                            :item="item"
                                            :value="getNestedValue(item, column.key)"
                                        />
                                        <span v-else>
                                            {{ column.format ? column.format(getNestedValue(item, column.key), item) : getNestedValue(item, column.key) }}
                                        </span>
                                    </slot>
                                </td>
                                <td v-if="showActions" class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <!-- Custom Actions Slot -->
                                    <slot name="actions" :item="item" :edit="() => editItem(item)" :remove="() => deleteItem(item.id)">
                                        <!-- Default Actions -->
                                        <div class="flex justify-end gap-2">
                                            <Link 
                                                v-if="editRouteName"
                                                :href="route(editRouteName, item.id)" 
                                                class="text-gray-400 hover:text-blue-600 transition-colors" 
                                                title="Editar"
                                            >
                                                <PencilSquareIcon class="h-5 w-5" />
                                            </Link>

                                            <button 
                                                v-if="allowDelete"
                                                @click="deleteItem(item.id)" 
                                                class="text-gray-400 hover:text-red-600 transition-colors"
                                                title="Eliminar"
                                            >
                                                <TrashIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </slot>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Per Page Selector & Pagination -->
        <div v-if="items.length > 0" class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Per Page Selector -->
            <div class="flex items-center gap-2">
                <label for="perPageSelect" class="text-sm text-gray-700">
                    Mostrar:
                </label>
                <select
                    id="perPageSelect"
                    v-model.number="currentPerPage"
                    @change="onPerPageChange"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                    <option :value="5">5</option>
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                </select>
                <span class="text-sm text-gray-700">
                    filas por página
                </span>
            </div>

            <!-- Pagination -->
            <div v-if="pagination && pagination.last_page > 1" class="flex justify-center gap-2">
                <button
                    v-for="p in pagination.last_page"
                    :key="p"
                    @click="page = p; fetchData()"
                    :class="[
                        'px-4 py-2 rounded border transition-colors',
                        p === pagination.current_page
                            ? 'bg-indigo-600 text-white border-indigo-600'
                            : 'bg-white text-gray-900 border-gray-300 hover:bg-gray-50'
                    ]"
                >
                    {{ p }}
                </button>
            </div>

            <!-- Total Count -->
            <div class="text-sm text-gray-700">
                <span v-if="pagination">
                    Total: {{ pagination.total }} {{ resourceNamePlural }}
                </span>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import ApiClient from '@/api/client';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    // Basic Info
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    
    // API Configuration
    apiUrl: {
        type: String,
        required: true,
    },
    apiToken: {
        type: String,
        required: true,
    },
    
    // Routes
    createRoute: {
        type: String,
        default: null,
    },
    editRouteName: {
        type: String,
        default: null,
    },
    
    // Table Configuration
    columns: {
        type: Array,
        required: true,
        // Example: [{ key: 'name', label: 'Nombre', format: (value) => value }]
    },
    
    // Search Configuration
    searchPlaceholder: {
        type: String,
        default: 'Buscar...',
    },
    searchFields: {
        type: Array,
        default: () => [],
    },
    searchOperator: {
        type: String,
        default: 'CONTAINS',
    },
    searchEnabled: {
        type: Boolean,
        default: true,
    },
    
    // Resource Names (for messages)
    resourceName: {
        type: String,
        default: 'elemento',
    },
    resourceNamePlural: {
        type: String,
        default: 'elementos',
    },
    
    // Actions
    allowDelete: {
        type: Boolean,
        default: true,
    },
    deleteConfirmMessage: {
        type: String,
        default: null,
    },
    
    // Create Button
    createButtonText: {
        type: String,
        default: 'Nuevo',
    },
    
    // Pagination
    perPage: {
        type: Number,
        default: 10,
    },

    // Sorting
    orderBy: {
        type: String,
        default: 'id',
    },
    order: {
        type: String,
        default: 'desc',
    },

    // Criteria Filters
    staticFilters: {
        type: Array,
        default: () => [],
    },
    initialDynamicFilters: {
        type: Array,
        default: () => [],
    },
    
    // Additional Query Params
    additionalParams: {
        type: Object,
        default: () => ({}),
    },
});

const api = new ApiClient(props.apiToken);

const items = ref([]);
const pagination = ref(null);
const search = ref('');
const page = ref(1);
const loading = ref(false);
const error = ref(null);
const dynamicFilters = ref([...props.initialDynamicFilters]);
const currentPerPage = ref(props.perPage);

const showActions = computed(() => props.editRouteName || props.allowDelete);
const effectiveSearchFields = computed(() => {
    if (props.searchFields.length > 0) {
        return props.searchFields;
    }

    return [...new Set(props.columns
        .map((column) => column.searchField || column.key)
        .filter((field) => typeof field === 'string' && field.length > 0))];
});

const normalizeFilter = (filter) => {
    if (!filter || !filter.field || !filter.operator) {
        return null;
    }

    return {
        field: String(filter.field),
        operator: String(filter.operator),
        value: filter.value ?? '',
    };
};

const validFilters = (filters) => {
    return (filters || [])
        .map(normalizeFilter)
        .filter((filter) => filter !== null && !(filter.value === '' && !['IS_NULL', 'IS_NOT_NULL'].includes(filter.operator)));
};

const setDynamicFilters = (filters = []) => {
    dynamicFilters.value = validFilters(filters);
    page.value = 1;
};

const addDynamicFilter = (filter) => {
    const normalized = normalizeFilter(filter);
    if (!normalized) {
        return;
    }

    dynamicFilters.value = [...dynamicFilters.value, normalized];
    page.value = 1;
};

const setDynamicFilter = ({ field, operator = 'EQUAL', value = '' }) => {
    if (!field) {
        return;
    }

    const fieldKey = String(field);
    const normalized = normalizeFilter({ field: fieldKey, operator, value });
    const current = dynamicFilters.value.filter((filter) => filter.field !== fieldKey);

    if (normalized && !(normalized.value === '' && !['IS_NULL', 'IS_NOT_NULL'].includes(normalized.operator))) {
        dynamicFilters.value = [...current, normalized];
    } else {
        dynamicFilters.value = current;
    }

    page.value = 1;
};

const clearDynamicFilters = () => {
    dynamicFilters.value = [];
    page.value = 1;
};

const buildCriteriaFilters = () => {
    const groups = [];

    const cleanedStaticFilters = validFilters(props.staticFilters);
    if (cleanedStaticFilters.length > 0) {
        groups.push({
            glue: 'and',
            conditions: cleanedStaticFilters,
        });
    }

    const cleanedDynamicFilters = validFilters(dynamicFilters.value);
    if (cleanedDynamicFilters.length > 0) {
        groups.push({
            glue: 'and',
            conditions: cleanedDynamicFilters,
        });
    }

    if (props.searchEnabled && search.value.trim().length > 0 && effectiveSearchFields.value.length > 0) {
        groups.push({
            glue: 'or',
            conditions: effectiveSearchFields.value.map((field) => ({
                field,
                operator: props.searchOperator,
                value: search.value.trim(),
            })),
        });
    }

    if (groups.length === 0) {
        return [];
    }

    return {
        groups,
    };
};

const getOffset = () => (page.value - 1) * currentPerPage.value;

const buildPagination = (responseData, currentItemsCount) => {
    // New API format includes all pagination info
    const currentPage = Number(responseData.current_page ?? page.value);
    const totalPages = Number(responseData.total_pages ?? 1);
    const total = Number(responseData.filtered_records ?? responseData.total_records ?? 0);
    const perPage = Number(responseData.per_page ?? currentPerPage.value);

    return {
        current_page: currentPage,
        last_page: totalPages,
        total,
        per_page: perPage,
    };
};

const onSearchInput = () => {
    page.value = 1;
    fetchData();
};

const onPerPageChange = () => {
    page.value = 1;
    fetchData();
};

const fetchData = async () => {
    loading.value = true;
    error.value = null;
    try {
        const filters = buildCriteriaFilters();
        const response = await api.get(props.apiUrl, {
            limit: currentPerPage.value,
            offset: getOffset(),
            order_by: props.orderBy,
            order: props.order,
            filters,
            ...props.additionalParams,
        });

        items.value = response.data.data.records || [];
        pagination.value = buildPagination(response.data.data, items.value.length);
    } catch (err) {
        error.value = `Error al cargar los ${props.resourceNamePlural}`;
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const deleteItem = async (id) => {
    const confirmMessage = props.deleteConfirmMessage || `¿Estás seguro de que deseas eliminar este ${props.resourceName}?`;
    
    if (!confirm(confirmMessage)) {
        return;
    }

    try {
        await api.delete(`${props.apiUrl}/${id}`);
        items.value = items.value.filter(item => item.id !== id);
    } catch (err) {
        error.value = `Error al eliminar el ${props.resourceName}`;
        console.error(err);
    }
};

const editItem = (item) => {
    if (props.editRouteName) {
        router.visit(route(props.editRouteName, item.id));
    }
};

// Helper function to get nested properties (e.g., 'category.name')
const getNestedValue = (obj, path) => {
    const value = path.split('.').reduce((acc, part) => acc?.[part], obj);

    return value ?? 'N/A';
};

// Expose methods for parent components
defineExpose({
    fetchData,
    items,
    search,
    page,
    dynamicFilters,
    setDynamicFilters,
    addDynamicFilter,
    setDynamicFilter,
    clearDynamicFilters,
});

onMounted(() => {
    fetchData();
});
</script>
