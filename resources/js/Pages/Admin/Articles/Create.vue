<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Crear Nuevo Artículo
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-8">
        <LocalizationTabs :markets="markets" v-model="form.localizations" :errors="errors" />
      </div>

      <div class="space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>

          <div class="space-y-4">

            <div>
              <label class="block text-sm font-medium text-gray-700">Categoría</label>
              <select v-model="form.article_category_id" :class="{ 'border-red-300': errors.article_category_id }"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option :value="null">Sin categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.title || 'Sin nombre' }}
                </option>
              </select>
              <p v-if="errors.article_category_id" class="mt-1 text-sm text-red-600">{{ errors.article_category_id }}
              </p>
            </div>

            <div class="flex items-center">
              <button type="button" @click="form.status = form.status === 'published' ? 'draft' : 'published'"
                :class="form.status === 'published' ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600">
                <span :class="form.status === 'published' ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
              </button>
              <span class="ml-3 text-sm font-medium text-gray-900">Publicado</span>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Imagen Destacada</h3>
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path
                  d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label
                  class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                  <span>Subir archivo</span>
                  <input type="file" class="sr-only" />
                </label>
                <p class="pl-1">o arrastrar y soltar</p>
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <Link :href="route('admin.articles.index')"
            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
            {{ processing ? 'Guardando...' : 'Guardar Artículo' }}
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

const props = defineProps({
  markets: { type: Array, required: true },
  categories: { type: Array, default: () => [] },
});

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
  article_category_id: null,
  status: 'published',
  images: [],
  localizations: {},
  content: [],
});

const errors = ref({});
const processing = ref(false);

const submit = async () => {
  processing.value = true;
  errors.value = {};

  const localizations = Object.values(form.value.localizations).filter(
    (loc) => (loc?.title || '').trim() !== ''
  );

  const payload = {
    article_category_id: form.value.article_category_id,
    status: form.value.status,
    images: form.value.images,
    localizations,
  };

  try {
    await api.post('/api/v1/articles', payload);
    router.visit(route('admin.articles.index'));
  } catch (e) {
    errors.value = e.response?.status === 422
      ? (e.response.data.errors || {})
      : { general: 'Error inesperado al guardar.' };
  } finally {
    processing.value = false;
  }
};
</script>
