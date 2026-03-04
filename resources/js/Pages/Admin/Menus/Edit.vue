<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Editar Menú</h1>
        <Link :href="route('admin.menus.items.index', menu.id)" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          Gestionar Enlaces del Menú
        </Link>
      </div>

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
              <input v-model="form.name.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['name.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
              <p v-if="form.errors['name.es']" class="mt-1 text-sm text-red-600">{{ form.errors['name.es'] }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Clave única</label>
              <input v-model="form.key" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.key}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" required />
               <p v-if="form.errors.key" class="mt-1 text-sm text-red-600">{{ form.errors.key }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Descripción (ES)</label>
              <textarea v-model="form.description.es" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (EN)</label>
              <input v-model="form.name.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['name.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
               <p v-if="form.errors['name.en']" class="mt-1 text-sm text-red-600">{{ form.errors['name.en'] }}</p>
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
            <div>
              <label class="block text-sm font-medium text-gray-700">Orden</label>
              <input v-model.number="form.sort_order" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div class="flex items-center">
              <input v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label class="ml-3 text-sm font-medium text-gray-900">Menú Activo</label>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.menus.index')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancelar
          </Link>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            Actualizar Menú
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const activeLang = ref('es');

const breadcrumbItems = [
  { label: 'Menús', link: route('admin.menus.index') },
  { label: 'Editar' }
];

const props = defineProps({
  menu: Object,
});

const form = useForm({
  name: props.menu.name || { es: '', en: '' },
  key: props.menu.key || '',
  description: props.menu.description || { es: '', en: '' },
  active: props.menu.active,
});

const submit = () => {
  form.put(route('admin.menus.update', props.menu.id));
};
</script>
