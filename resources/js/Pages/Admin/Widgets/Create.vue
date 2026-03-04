<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="max-w-4xl">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Crear Widget</h1>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Zone Selection -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Ubicación</h3>
          <div>
            <label class="block text-sm font-medium text-gray-700">Zona</label>
            <select v-model="form.zone_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
              <option :value="null">Seleccionar zona...</option>
              <option v-for="zone in zones" :key="zone.id" :value="zone.id">
                {{ zone.name?.es || zone.name?.en }} ({{ zone.key }})
              </option>
            </select>
          </div>
        </div>

        <!-- Widget Type -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Tipo de Widget</h3>
          <div class="grid grid-cols-2 gap-4">
            <button type="button" @click="form.type = 'menu'" :class="form.type === 'menu' ? 'ring-2 ring-indigo-600 bg-indigo-50' : 'ring-1 ring-gray-300'" class="p-4 rounded-lg text-left hover:bg-gray-50 transition">
              <div class="font-medium text-gray-900">Menú</div>
              <div class="text-sm text-gray-500">Mostrar un menú de navegación</div>
            </button>
            <button type="button" @click="form.type = 'form'" :class="form.type === 'form' ? 'ring-2 ring-indigo-600 bg-indigo-50' : 'ring-1 ring-gray-300'" class="p-4 rounded-lg text-left hover:bg-gray-50 transition">
              <div class="font-medium text-gray-900">Formulario</div>
              <div class="text-sm text-gray-500">Insertar un formulario</div>
            </button>
            <button type="button" @click="form.type = 'wysiwyg'" :class="form.type === 'wysiwyg' ? 'ring-2 ring-indigo-600 bg-indigo-50' : 'ring-1 ring-gray-300'" class="p-4 rounded-lg text-left hover:bg-gray-50 transition">
              <div class="font-medium text-gray-900">Contenido WYSIWYG</div>
              <div class="text-sm text-gray-500">Contenido de texto enriquecido</div>
            </button>
            <button type="button" @click="form.type = 'fixed_content'" :class="form.type === 'fixed_content' ? 'ring-2 ring-indigo-600 bg-indigo-50' : 'ring-1 ring-gray-300'" class="p-4 rounded-lg text-left hover:bg-gray-50 transition">
              <div class="font-medium text-gray-900">Contenido Fijo</div>
              <div class="text-sm text-gray-500">Bloque de contenido predefinido</div>
            </button>
          </div>
        </div>

        <!-- Widget Configuration -->
        <div v-if="form.type" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración del Widget</h3>
          
          <!-- Menu Widget Config -->
          <div v-if="form.type === 'menu'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Menú</label>
              <select v-model="form.config.menu_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option :value="null">Seleccionar menú...</option>
                <option v-for="menu in menus" :key="menu.id" :value="menu.id">
                  {{ menu.name?.es || menu.name?.en }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Estilo</label>
              <select v-model="form.config.style" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="vertical">Vertical</option>
                <option value="horizontal">Horizontal</option>
              </select>
            </div>
          </div>

          <!-- Form Widget Config -->
          <div v-if="form.type === 'form'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Formulario</label>
              <select v-model="form.config.form_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option :value="null">Seleccionar formulario...</option>
                <option v-for="formItem in forms" :key="formItem.id" :value="formItem.id">
                  {{ formItem.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- WYSIWYG Widget Config -->
          <div v-if="form.type === 'wysiwyg'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Contenido (ES)</label>
              <textarea v-model="form.config.content_es" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contenido (EN)</label>
              <textarea v-model="form.config.content_en" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
          </div>

          <!-- Fixed Content Widget Config -->
          <div v-if="form.type === 'fixed_content'" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Clave de Contenido</label>
              <input v-model="form.config.content_key" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="footer-info" required />
            </div>
          </div>
        </div>

        <!-- Settings -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración General</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Título (ES)</label>
              <input v-model="form.title.es" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Título (EN)</label>
              <input v-model="form.title.en" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Orden</label>
              <input v-model.number="form.sort_order" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
            <div class="flex items-center">
              <input v-model="form.active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
              <label class="ml-3 text-sm font-medium text-gray-900">Widget Activo</label>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.widgets.index')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancelar
          </Link>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            Crear Widget
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
import { watch } from 'vue';

const props = defineProps({
  zones: Array,
  menus: Array,
  forms: Array,
});

const breadcrumbItems = [
  { label: 'Widgets', link: route('admin.widgets.index') },
  { label: 'Crear' }
];

const form = useForm({
  zone_id: null,
  type: null,
  title: { es: '', en: '' },
  config: {},
  sort_order: 0,
  active: true,
});

// Reset config when type changes
watch(() => form.type, (newType) => {
  if (newType === 'menu') {
    form.config = { menu_id: null, style: 'vertical' };
  } else if (newType === 'form') {
    form.config = { form_id: null };
  } else if (newType === 'wysiwyg') {
    form.config = { content_es: '', content_en: '' };
  } else if (newType === 'fixed_content') {
    form.config = { content_key: '' };
  }
});

const submit = () => {
  form.post(route('admin.widgets.store'));
};
</script>
