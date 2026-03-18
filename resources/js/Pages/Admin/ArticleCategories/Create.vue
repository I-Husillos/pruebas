<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Crear Categoría de Artículo</h1>

      <form @submit.prevent="submit" class="space-y-8">

        <!--
          TranslationTabs gestiona internamente:
          - Las pestañas por idioma (dinámicas, vienen del backend)
          - Título con auto-slug
          - Descripción
          v-model sincroniza el objeto translations con este formulario
        -->
        <TranslationTabs
          :languages="languages"
          v-model="form.translations"
          :errors="errors"
          :submittedOrder="getSubmittedTranslationsOrder()"
        />

        <!-- Configuración -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración</h3>
          <div class="flex items-center">
            <input
              v-model="form.active"
              type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
            />
            <label class="ml-3 text-sm font-medium text-gray-900">Categoría Activa</label>
          </div>
        </div>

        <div class="flex justify-end gap-3">
          <Link :href="route('admin.article-categories.index')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
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
import { useArticleCategoryForm } from '@/Composables/Admin/useArticleCategoryForm';

const props = defineProps({
  languages: { type: Array, required: true },
  translations: { type: Array, default: () => [] },
});

const api = new ApiClient(usePage().props.apiToken);

const breadcrumbItems = [
  { label: 'Categorías de Artículos', link: route('admin.article-categories.index') },
  { label: 'Crear' },
];

const { form, errors, processing, submitCreate, getSubmittedTranslationsOrder } = useArticleCategoryForm({
  api,
  languages: props.languages,
  onSuccess: () => router.visit(route('admin.article-categories.index')),
  translations: props.translations,
})

const submit = () => submitCreate()
</script>