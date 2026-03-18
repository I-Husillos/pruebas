<template>
    <AdminLayout>
        <Breadcrumbs :items="breadcrumbItems" />

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Editar Página
                </h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <LocalizationTabs
                    :markets="markets"
                    v-model="form.localizations"
                    :errors="errors"
                    :forms="forms"
                    :on-delete-localization="deleteLocalization"
                />
            </div>

            <div class="space-y-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select
                                v-model="form.status"
                                :class="hasError('status')
                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                                    : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                            >
                                <option value="draft">Borrador</option>
                                <option value="published">Publicado</option>
                                <option value="scheduled">Programado</option>
                                <option value="pending_review">Pendiente de revisión</option>
                            </select>
                            <p v-if="hasError('status')" class="mt-1 text-sm text-red-600">{{ getFieldError('status') }}</p>
                        </div>
                    </div>
                </div>

                <p v-if="hasError('general')" class="text-sm text-red-600">{{ getFieldError('general') }}</p>

                <div class="flex justify-end gap-3 pt-4">
                    <Link
                        :href="route('admin.pages.index')"
                        class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ processing ? 'Guardando...' : 'Actualizar Página' }}
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import LocalizationTabs from '@/Components/Admin/LocalizationTabs.vue';
import ApiClient from '@/api/client';
import { buildInitialPageLocalizations } from '@/Composables/Admin/usePageLocalizations';
import { usePageForm } from '@/Composables/Admin/usePageForm';
import { getErrorMessage, hasError as hasValidationError } from '@/utils/errors';

const props = defineProps({
    page: { type: Object, required: true },
    markets: { type: Array, required: true },
    forms: { type: Array, default: () => [] },
});

const breadcrumbItems = [
    { label: 'Páginas', link: route('admin.pages.index') },
    { label: 'Editar' },
];

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
    status: props.page.status ?? 'draft',
    localizations: buildInitialPageLocalizations(
        props.page.localizations ?? [],
        props.markets
    ),
});

const { errors, processing, submitUpdate, removeLocalization } = usePageForm({
    api,
    onSuccess: () => router.visit(route('admin.pages.index')),
});

const hasError = (field) => hasValidationError(errors.value, field);
const getFieldError = (field) => getErrorMessage(errors.value, field);

const submit = () => submitUpdate(props.page.id, form.value);

async function deleteLocalization(localizationId) {
    if (!confirm('¿Eliminar esta localización? Se perderá todo su contenido.')) {
        return;
    }

    const result = await removeLocalization(localizationId);
    if (!result.ok) {
        alert(result.message);
        return;
    }

    router.visit(route('admin.pages.edit', props.page.id));
}
</script>
