<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Crear Categoría de Tratamiento</h1>

      <form @submit.prevent="submit" class="space-y-8">
        <TranslationTabs
          :languages="languages"
          v-model="form.translations"
          :errors="errors"
        />

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración</h3>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-2">
              <input v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label class="text-sm font-medium text-gray-900">Categoría activa</label>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Orden</label>
              <input v-model="form.order" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3">
          <Link :href="route('admin.treatment-categories.index')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancelar</Link>
          <button type="submit" :disabled="processing" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            {{ processing ? 'Guardando...' : 'Crear Categoría' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import TranslationTabs from '@/Components/Admin/TranslationTabs.vue';
import ApiClient from '@/api/client';
import { useTreatmentCategoryForm } from '@/Composables/Admin/useTreatmentCategoryForm';

const props = defineProps({
  languages: { type: Array, required: true },
});

const api = new ApiClient(usePage().props.apiToken);

const breadcrumbItems = [
  { label: 'Categorías de Tratamientos', link: route('admin.treatment-categories.index') },
  { label: 'Crear' },
];

const { form, errors, processing, submitCreate } = useTreatmentCategoryForm({
  api,
  languages: props.languages,
  onSuccess: () => router.visit(route('admin.treatment-categories.index')),
});

const submit = () => submitCreate();
</script>
