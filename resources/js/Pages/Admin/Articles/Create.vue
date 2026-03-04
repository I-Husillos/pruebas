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
              <input v-model="form.title.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['title.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
              <p v-if="form.errors['title.es']" class="mt-1 text-sm text-red-600">{{ form.errors['title.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Extracto / SEO Description (ES)</label>
              <textarea v-model="form.excerpt.es" rows="3" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['excerpt.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
              <p v-if="form.errors['excerpt.es']" class="mt-1 text-sm text-red-600">{{ form.errors['excerpt.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contenido (ES)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.content.es" />
                <p v-if="form.errors['content.es']" class="mt-1 text-sm text-red-600">{{ form.errors['content.es'] }}</p>
              </div>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
              <input v-model="form.title.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['title.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="form.errors['title.en']" class="mt-1 text-sm text-red-600">{{ form.errors['title.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Excerpt / SEO Description (EN)</label>
              <textarea v-model="form.excerpt.en" rows="3" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['excerpt.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
               <p v-if="form.errors['excerpt.en']" class="mt-1 text-sm text-red-600">{{ form.errors['excerpt.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Content (EN)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.content.en" />
                <p v-if="form.errors['content.en']" class="mt-1 text-sm text-red-600">{{ form.errors['content.en'] }}</p>
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
              <select v-model="form.type" id="type" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.type}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="blog">Blog / Noticia</option>
                <option value="news">Novedad</option>
                <option value="press">Prensa</option>
              </select>
              <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
            </div>

            <div>
              <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
              <select v-model="form.category_id" id="category" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.category_id}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option :value="null">Sin categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name?.es || category.name?.en || 'Sin nombre' }}
                </option>
              </select>
              <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
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
              <input v-model="form.published_at" type="datetime-local" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.published_at}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="form.errors.published_at" class="mt-1 text-sm text-red-600">{{ form.errors.published_at }}</p>
            </div>
          </div>
        </div>

        <!-- Featured Image (Placeholder for now) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Imagen Destacada</h3>
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                  <span>Subir archivo</span>
                  <input id="file-upload" name="file-upload" type="file" class="sr-only" />
                </label>
                <p class="pl-1">o arrastrar y soltar</p>
              </div>
              <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <Link :href="route('admin.articles.index')" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="form.processing" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
            Guardar Artículo
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
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbItems = [
  { label: 'Artículos', link: route('admin.articles.index') },
  { label: 'Crear' }
];

const activeLang = ref('es');

defineProps({
  categories: Array,
});

const form = useForm({
  type: 'blog',
  title: { es: '', en: '' },
  slug: { es: '', en: '' },
  excerpt: { es: '', en: '' },
  content: { es: '', en: '' },
  published: true,
  published_at: new Date().toISOString().slice(0, 16),
  category_id: null,
  featured_image: null,
});

const submit = () => {
  form.post(route('admin.articles.store'));
};
</script>
