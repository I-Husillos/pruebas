<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Crear Nuevo Tratamiento
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-8 divide-y divide-gray-200">
      <div class="space-y-6 sm:space-y-5">
        <!-- Basic Information -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración</h3>
          
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="sort_order" class="block text-sm font-medium text-gray-700">Orden de Visualización</label>
              <div class="mt-1">
                <input v-model="form.sort_order" type="number" id="sort_order" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0" />
              </div>
            </div>
          </div>
        </div>

        <!-- Localization (Tabs) -->
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
              <label class="block text-sm font-medium text-gray-700">Nombre del Tratamiento (ES)</label>
              <input v-model="form.name.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['name.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
              <p v-if="form.errors['name.es']" class="mt-1 text-sm text-red-600">{{ form.errors['name.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Slug (ES)</label>
              <input v-model="form.slug.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['slug.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500" />
              <p v-if="form.errors['slug.es']" class="mt-1 text-sm text-red-600">{{ form.errors['slug.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Descripción detallada (ES)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.description.es" />
                 <p v-if="form.errors['description.es']" class="mt-1 text-sm text-red-600">{{ form.errors['description.es'] }}</p>
              </div>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Treatment Name (EN)</label>
              <input v-model="form.name.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['name.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="form.errors['name.en']" class="mt-1 text-sm text-red-600">{{ form.errors['name.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Slug (EN)</label>
              <input v-model="form.slug.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['slug.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500" />
              <p v-if="form.errors['slug.en']" class="mt-1 text-sm text-red-600">{{ form.errors['slug.en'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Detailed Description (EN)</label>
              <div class="mt-1">
                <Wysiwyg v-model="form.description.en" />
                 <p v-if="form.errors['description.en']" class="mt-1 text-sm text-red-600">{{ form.errors['description.en'] }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado</h3>
          <div class="flex items-center">
            <button 
              type="button" 
              @click="form.published = !form.published"
              :class="form.published ? 'bg-indigo-600' : 'bg-gray-200'"
              class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
            >
              <span :class="form.published ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
            </button>
            <span class="ml-3 text-sm font-medium text-gray-900">Tratamiento Activo/Publicado</span>
          </div>
          
          <!-- Category Selector -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
            <select v-model="form.category_id" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.category_id}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option :value="null">Sin categoría</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name?.es || category.name?.en || 'Sin nombre' }}
              </option>
            </select>
             <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
          </div>

          <!-- Markets Selector -->
          <div class="mt-6 border-t border-gray-100 pt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Mercados Disponibles</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
              <div v-for="market in markets" :key="market.id" class="flex items-center">
                <input 
                  type="checkbox" 
                  :value="market.code" v-model="form.available_markets"
                  class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                />
                <label class="ml-2 text-sm text-gray-700">
                  {{ market.name }} <span class="text-xs text-gray-500">({{ market.code }})</span>
                </label>
              </div>
            </div>
            <p v-if="form.errors.available_markets" class="mt-1 text-sm text-red-600">{{ form.errors.available_markets }}</p>
          </div>
        </div>

        <!-- Advanced Content Builder -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Constructor de Contenido Avanzado</h3>
            <p class="text-sm text-gray-500 mb-4">Utiliza este constructor para añadir más secciones con diseños complejos.</p>
            <BlockEditor v-model="form.blocks_json" :forms="forms" />
        </div>
      </div>

      <div class="pt-5">
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.treatments.index')" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="form.processing" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
            <span v-if="form.processing">Guardando...</span>
            <span v-else>Guardar Tratamiento</span>
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
import BlockEditor from '@/Components/Admin/BlockEditor.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    forms: Array,
    categories: Array,
    markets: Array,
});

const breadcrumbItems = [
  { label: 'Tratamientos', link: route('admin.treatments.index') },
  { label: 'Crear' }
];

const activeLang = ref('es');

const form = useForm({
  name: { es: '', en: '' },
  slug: { es: '', en: '' },
  description: { es: '', en: '' },
  sort_order: 0,
  published: true,
  available_markets: ['es'],
  category_id: null,
  blocks_json: [],
});

const submit = () => {
  form.post(route('admin.treatments.store'));
};
</script>
