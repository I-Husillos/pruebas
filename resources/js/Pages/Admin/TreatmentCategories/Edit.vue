<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Editar Categoría de Tratamiento</h1>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Localization Tabs -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Contenido Localizado</h3>
            <div class="flex space-x-2">
              <button v-for="lang in ['es', 'en']" :key="lang" type="button" @click="activeLang = lang"
                :class="activeLang === lang ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-3 py-1 rounded text-xs font-bold uppercase transition-colors">
                {{ lang }}
              </button>
            </div>
          </div>

          <div v-show="activeLang === 'es'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Nombre (ES)</label>
              <input v-model="form.title.es" type="text" :class="{'ring-red-300 focus:ring-red-600': errors['title.es'], 'ring-gray-300 focus:ring-indigo-600': !errors['title.es']}" class="mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset py-1.5 focus:ring-2 focus:ring-inset sm:text-sm" required />
              <div v-if="errors['title.es']" class="mt-1 text-sm text-red-600">{{ errors['title.es'] }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Slug (ES)</label>
              <input v-model="form.slug.es" type="text" :class="{'ring-red-300 focus:ring-red-600': errors['slug.es'], 'ring-gray-300 focus:ring-indigo-600': !errors['slug.es']}" class="mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset py-1.5 focus:ring-2 focus:ring-inset sm:text-sm" required />
              <div v-if="errors['slug.es']" class="mt-1 text-sm text-red-600">{{ errors['slug.es'] }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Descripción (ES)</label>
              <textarea v-model="form.description.es" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (EN)</label>
              <input v-model="form.title.en" type="text" :class="{'ring-red-300 focus:ring-red-600': errors['title.en'], 'ring-gray-300 focus:ring-indigo-600': !errors['title.en']}" class="mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset py-1.5 focus:ring-2 focus:ring-inset sm:text-sm" />
              <div v-if="errors['title.en']" class="mt-1 text-sm text-red-600">{{ errors['title.en'] }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Slug (EN)</label>
              <input v-model="form.slug.en" type="text" :class="{'ring-red-300 focus:ring-red-600': errors['slug.en'], 'ring-gray-300 focus:ring-indigo-600': !errors['slug.en']}" class="mt-1 block w-full rounded-md border-0 shadow-sm ring-1 ring-inset py-1.5 focus:ring-2 focus:ring-inset sm:text-sm" />
              <div v-if="errors['slug.en']" class="mt-1 text-sm text-red-600">{{ errors['slug.en'] }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
              <textarea v-model="form.description.en" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración</h3>
          <div class="space-y-4">
            <div class="flex items-center">
              <input v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label class="ml-3 text-sm font-medium text-gray-900">Categoría Activa</label>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.treatment-categories.index')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            Actualizar Categoría
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApiClient from '@/api/client';


const props = defineProps({
  category: Object,
});

const api = new ApiClient(usePage().props.apiToken);

const activeLang = ref('es');

const breadcrumbItems = [
  { label: 'Categorías de Tratamientos', link: route('admin.treatment-categories.index') },
  { label: 'Editar' }
];

const form = ref({
  title: {
    es: props.category?.translations?.find((t) => Number(t.language_id) === 1)?.title || '',
    en: props.category?.translations?.find((t) => Number(t.language_id) === 2)?.title || '',
  },
  slug: {
    es: props.category?.translations?.find((t) => Number(t.language_id) === 1)?.slug || '',
    en: props.category?.translations?.find((t) => Number(t.language_id) === 2)?.slug || '',
  },
  description: {
    es: props.category?.translations?.find((t) => Number(t.language_id) === 1)?.description || '',
    en: props.category?.translations?.find((t) => Number(t.language_id) === 2)?.description || '',
  },
  active: props.category?.status === 'active',
  sort_order: Number(props.category?.order ?? 0),
});

const errors = ref({});
const processing = ref(false);

const submit = async () => {
  console.log('Datos que se envían:', JSON.stringify(form.value));
    processing.value = true;
    errors.value = {};

    try {
        await api.put(`/api/v1/treatment-categories/${props.category.id}`, {
          status: form.value.active ? 'active' : 'inactive',
          order: Number(form.value.sort_order ?? 0),
          translations: [
            ...(form.value.title.es?.trim() && form.value.slug.es?.trim() ? [{
              language_id: 1,
              title: form.value.title.es.trim(),
              slug: form.value.slug.es.trim(),
              description: form.value.description.es?.trim() || null,
              seo_metadata: null,
            }] : []),
            ...(form.value.title.en?.trim() && form.value.slug.en?.trim() ? [{
              language_id: 2,
              title: form.value.title.en.trim(),
              slug: form.value.slug.en.trim(),
              description: form.value.description.en?.trim() || null,
              seo_metadata: null,
            }] : []),
          ],
        });
        router.visit(route('admin.treatment-categories.index'));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors ?? {};
        } else {
            errors.value = { general: 'Error al actualizar la categoría.' };
        }
    } finally {
        processing.value = false;
    }
};
</script>
