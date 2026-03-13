<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Editar Artículo
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Columna principal: el componente reutilizable gestiona todo el contenido -->
      <div class="lg:col-span-2 space-y-8">
        <LocalizationTabs
          :markets="markets"
          v-model="form.localizations"
          :errors="errors"
        />
      </div>

      <!-- Sidebar: metadatos de la tabla maestra `articles` -->
      <div class="space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Categoría</label>
              <select
                v-model="form.article_category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option :value="null">Sin categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.title || 'Sin nombre' }}
                </option>
              </select>
            </div>

            <div class="flex items-center">
              <button
                type="button"
                @click="form.status = form.status === 'published' ? 'draft' : 'published'"
                :class="form.status === 'published' ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600"
              >
                <span
                  :class="form.status === 'published' ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                />
              </button>
              <span class="ml-3 text-sm font-medium text-gray-900">Publicado</span>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <Link
            :href="route('admin.articles.index')"
            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
          >
            {{ processing ? 'Guardando...' : 'Actualizar Artículo' }}
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
  article:    { type: Object, required: true },
  markets:    { type: Array,  required: true },
  categories: { type: Array,  default: () => [] },
});

const breadcrumbItems = [
  { label: 'Artículos', link: route('admin.articles.index') },
  { label: 'Editar' },
];

const api = new ApiClient(usePage().props.apiToken);

// Convertimos el array de localizaciones que viene del backend
// al formato de objeto que LocalizationTabs espera via v-model.
//
// El backend devuelve (desde ArticleResponse.toArray()):
//   localizations: [
//     { market_id: 1, language_id: 1, title: "Mi artículo", content: [...], ... },
//     { market_id: 1, language_id: 2, title: "My article",  content: [...], ... },
//   ]
//
// LocalizationTabs espera recibir por v-model un objeto:
//   { "es_es": { market_id: 1, language_id: 1, title: "Mi artículo", ... },
//     "es_en": { market_id: 1, language_id: 2, title: "My article",  ... } }
//
// Esta función hace esa transformación buscando los códigos
// de mercado e idioma a partir de los IDs.
function buildInitialLocalizations(locs, markets) {
  const result = {};
  for (const loc of locs ?? []) {
    const market = markets.find(m => m.id === loc.market_id);
    const lang   = market?.languages.find(l => l.id === loc.language_id);
    if (market && lang) {
      result[`${market.code}_${lang.code}`] = { ...loc };
    }
  }
  return result;
}

const form = ref({
  article_category_id: props.article.article_category_id,
  status:              props.article.status,
  images:              props.article.images ?? [],
  // Aquí pre-cargamos las localizaciones existentes.
  // LocalizationTabs las recibirá en su modelValue y las cargará
  // con Object.assign en su estado interno al montarse.
  localizations: buildInitialLocalizations(props.article.localizations, props.markets),
});

const errors     = ref({});
const processing = ref(false);

async function submit() {
  // Filtramos localizaciones vacías, igual que en Create.vue
  const filledLocalizations = Object.values(form.value.localizations)
    .filter(loc => (loc?.title || '').trim() !== '');

  const payload = {
    article_category_id: form.value.article_category_id,
    status:              form.value.status,
    images:              form.value.images,
    localizations:       filledLocalizations,
  };

  processing.value = true;
  errors.value     = {};

  try {
    await api.put(`/api/v1/articles/${props.article.id}`, payload);
    router.visit(route('admin.articles.index'));
  } catch (e) {
    errors.value = e.response?.status === 422
      ? (e.response.data.errors ?? {})
      : { general: 'Error al actualizar el artículo.' };
  } finally {
    processing.value = false;
  }
}
</script>