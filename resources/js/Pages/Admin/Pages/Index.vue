<template>
    <ResourceListTable
        title="Páginas"
        description="Páginas de aterrizaje y contenido específico por mercado e idioma"
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.pages.create')"
        edit-route-name="admin.pages.edit"
        :columns="columns"
        search-placeholder="Buscar páginas..."
        resource-name="página"
        resource-name-plural="páginas"
        create-button-text="Crear Página"
    >
        <template #cell-title="{ item }">
            <span>{{ firstLocalization(item)?.title || '—' }}</span>
        </template>

        <template #cell-slug="{ item }">
            <span>{{ firstLocalization(item)?.slug || '—' }}</span>
        </template>

        <template #cell-status="{ value }">
            <StatusBadge :value="value === 'published'" :label="statusLabel(value)" />
        </template>

        <template #actions="{ item, edit, remove }">
            <div class="flex justify-end gap-2">
                <a
                    v-if="firstLocalization(item)?.slug"
                    :href="`/pages/${firstLocalization(item).slug}`"
                    target="_blank"
                    class="text-gray-400 hover:text-indigo-600 transition-colors"
                    title="Ver en web"
                >
                    <EyeIcon class="h-5 w-5" />
                </a>

                <button @click="edit" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                    <PencilSquareIcon class="h-5 w-5" />
                </button>

                <button @click="remove" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                    <TrashIcon class="h-5 w-5" />
                </button>
            </div>
        </template>
    </ResourceListTable>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';
import StatusBadge from '@/Components/Admin/StatusBadge.vue';
import { EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const { props } = usePage();
const { apiToken, apiUrl } = props;

const columns = [
    { key: 'title', label: 'Título' },
    { key: 'slug', label: 'Slug' },
    { key: 'status', label: 'Estado' },
];

function firstLocalization(item) {
    return item?.localizations?.[0] ?? null;
}

function statusLabel(status) {
    return {
        draft: 'Borrador',
        published: 'Publicado',
        scheduled: 'Programado',
        pending_review: 'Pendiente de revisión',
    }[status] || status;
}
</script>
