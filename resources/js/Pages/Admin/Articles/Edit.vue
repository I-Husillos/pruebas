<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Editar Artículo: {{ article.title.es || article.title.en }}
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Contenido Localizado</h3>
            <div class="flex space-x-2">
              <button 
                v-for="lang in ['es', 'en']" 
                :key="lang"
                type="button"
                @click="activeLang = lang"
                :class="activeLang === lang ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-3 py-1 rounded text-xs font-bold uppercase transition-colors"
              >
                {{ lang }}
              </button>
            </div>
          </div>

          <div v-show="activeLang === 'es'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Título (ES)</label>
              <input v-model="form.title.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors['title.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
              <p v-if="errors['title.es']" class="mt-1 text-sm text-red-600">{{ errors['title.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Extracto / SEO Description (ES)</label>
              <textarea v-model="form.excerpt.es" rows="3" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors['excerpt.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
              <p v-if="errors['excerpt.es']" class="mt-1 text-sm text-red-600">{{ errors['excerpt.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contenido (ES)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.content.es" />
                <p v-if="errors['content.es']" class="mt-1 text-sm text-red-600">{{ errors['content.es'] }}</p>
              </div>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
              <input v-model="form.title.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors['title.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="errors['title.en']" class="mt-1 text-sm text-red-600">{{ errors['title.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Excerpt / SEO Description (EN)</label>
              <textarea v-model="form.excerpt.en" rows="3" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors['excerpt.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
              <p v-if="errors['excerpt.en']" class="mt-1 text-sm text-red-600">{{ errors['excerpt.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Content (EN)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.content.en" />
                 <p v-if="errors['content.en']" class="mt-1 text-sm text-red-600">{{ errors['content.en'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-8">
        <!-- Publishing Options -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>
          
          <div class="space-y-4">
            <div>
              <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Artículo</label>
              <select v-model="form.type" id="type" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.type}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="blog">Blog / Noticia</option>
                <option value="news">Novedad</option>
                <option value="press">Prensa</option>
              </select>
              <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
            </div>

            <div>
              <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
              <select v-model="form.category_id" id="category" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.category_id}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option :value="null">Sin categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name?.es || category.name?.en || 'Sin nombre' }}
                </option>
              </select>
              <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
            </div>

            <div class="flex items-center">
              <button 
                type="button" 
                @click="form.published = !form.published"
                :class="form.published ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
              >
                <span :class="form.published ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
              </button>
              <span class="ml-3 text-sm font-medium text-gray-900">Publicado</span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Fecha de Publicación</label>
              <input v-model="form.published_at" type="datetime-local" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.published_at}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="errors.published_at" class="mt-1 text-sm text-red-600">{{ errors.published_at }}</p>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <Link :href="route('admin.articles.index')" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
            Actualizar Artículo
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import Wysiwyg from '@/Components/Wysiwyg.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApiClient from '@/api/client';

const props = defineProps({
  article: Object,
  categories: Array,
});

const breadcrumbItems = [
  { label: 'Artículos', link: route('admin.articles.index') },
  { label: 'Editar' }
];

const api = new ApiClient(usePage().props.apiToken);

const activeLang = ref('es');

const form = ref({
  type: props.article.type,
  title: { 
    es: props.article.title.es || '', 
    en: props.article.title.en || '' 
  },
  slug: { 
    es: props.article.slug.es || '', 
    en: props.article.slug.en || '' 
  },
  excerpt: { 
    es: props.article.excerpt?.es || '', 
    en: props.article.excerpt?.en || '' 
  },
  content: { 
    es: props.article.content?.es || '', 
    en: props.article.content?.en || '' 
  },
  published: props.article.published,
  published_at: props.article.published_at ? new Date(props.article.published_at).toISOString().slice(0, 16) : null,
  category_id: props.article.category_id || null,
  featured_image: props.article.featured_image,
});


const errors = ref({});
const processing = ref(false);

const submit = async () => {
  console.log('Datos que se envían:', JSON.stringify(form.value));
    processing.value = true;
    errors.value = {};

    try {
        await api.put(`/api/v1/articles/${props.article.id}`, form.value);
        router.visit(route('admin.articles.index'));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors ?? {};
        } else {
            errors.value = { general: 'Error al actualizar el articulo.' };
        }
    } finally {
        processing.value = false;
    }
};
</script>
