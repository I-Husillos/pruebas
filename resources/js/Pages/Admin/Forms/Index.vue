<template>
    <ResourceListTable
        title="Formularios"
        description="Gestión de formularios y sus recepciones."
        :api-url="apiUrl"
        :api-token="apiToken"
        :create-route="route('admin.forms.create')"
        edit-route-name="admin.forms.edit"
        :columns="columns"
        search-placeholder="Buscar formularios..."
        resource-name="formulario"
        resource-name-plural="formularios"
        create-button-text="Nuevo Formulario"
    >
        <!-- Custom Submission Count with Link -->
        <template #cell-submission_count="{ item, value }">
            <Link :href="route('forms.submissions.index', item.id)" class="text-indigo-600 hover:text-indigo-900">
                {{ value }} envíos
            </Link>
        </template>
    </ResourceListTable>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ResourceListTable from '@/Components/Admin/ResourceListTable.vue';

const { props } = usePage();
const { apiToken, apiUrl } = props;

const columns = [
    { key: 'name', label: 'Nombre' },
    { key: 'key', label: 'Code (Key)' },
    { key: 'recipient_email', label: 'Destinatario' },
    { key: 'submission_count', label: 'Envíos' },
];
</script>
