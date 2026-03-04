<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Crear Item de Menú</h1>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Localization -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Etiqueta del Item</h3>
            <div class="flex space-x-2">
              <button v-for="lang in ['es', 'en']" :key="lang" type="button" @click="activeLang = lang"
                :class="activeLang === lang ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                class="px-3 py-1 rounded text-xs font-bold uppercase transition-colors">
                {{ lang }}
              </button>
            </div>
          </div>

          <div v-show="activeLang === 'es'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Etiqueta (ES)</label>
              <input v-model="form.label.es" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['label.es']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required />
              <p v-if="form.errors['label.es']" class="mt-1 text-sm text-red-600">{{ form.errors['label.es'] }}</p>
            </div>
          </div>

          <div v-show="activeLang === 'en'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Label (EN)</label>
              <input v-model="form.label.en" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors['label.en']}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="form.errors['label.en']" class="mt-1 text-sm text-red-600">{{ form.errors['label.en'] }}</p>
            </div>
          </div>
        </div>

        <!-- Link Configuration -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración del Enlace</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">URL Externa</label>
              <input v-model="form.url" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.url}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="https://ejemplo.com" />
              <p v-if="form.errors.url" class="mt-1 text-sm text-red-600">{{ form.errors.url }}</p>
              <p class="mt-1 text-xs text-gray-500">O deja vacío para usar una ruta interna</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Ruta Interna</label>
              <input v-model="form.route_name" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.route_name}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" placeholder="products.index.es" />
              <p v-if="form.errors.route_name" class="mt-1 text-sm text-red-600">{{ form.errors.route_name }}</p>
              <p class="mt-1 text-xs text-gray-500">Nombre de la ruta Laravel</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Icono (opcional)</label>
              <input v-model="form.icon" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="heroicon-home" />
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Item Padre</label>
              <select v-model="form.parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option :value="null">Ninguno (nivel superior)</option>
                <option v-for="item in parentItems" :key="item.id" :value="item.id">
                  {{ item.label?.es || item.label?.en }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Destino del Enlace</label>
              <select v-model="form.target" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="_self">Misma ventana</option>
                <option value="_blank">Nueva ventana</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Orden</label>
              <input v-model.number="form.sort_order" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div class="flex items-center">
              <input v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label class="ml-3 text-sm font-medium text-gray-900">Item Activo</label>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.menus.items.index', menu.id)" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancelar
          </Link>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            Crear Item
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

const props = defineProps({
  menu: Object,
  parentItems: Array,
});

const activeLang = ref('es');

const breadcrumbItems = [
  { label: 'Menús', link: route('admin.menus.index') },
  { label: props.menu.name?.es || 'Menú', link: route('admin.menus.edit', props.menu.id) },
  { label: 'Items', link: route('admin.menus.items.index', props.menu.id) },
  { label: 'Crear' }
];

const form = useForm({
  label: { es: '', en: '' },
  url: '',
  route_name: '',
  route_params: {},
  parent_id: null,
  icon: '',
  target: '_self',
  sort_order: 0,
  active: true,
});

const submit = () => {
  form.post(route('admin.menus.items.store', props.menu.id));
};
</script>
