<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Añadir Nuevo Idioma
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-8 divide-y divide-gray-200">
      <div class="space-y-6 sm:space-y-5">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Identificación</h3>
          
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="name" class="block text-sm font-medium text-gray-700">Nombre (En Español)</label>
              <input v-model="form.name" type="text" id="name" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.name}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Inglés, Francés..." required />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div class="sm:col-span-3">
              <label for="native_name" class="block text-sm font-medium text-gray-700">Nombre Nativo</label>
              <input v-model="form.native_name" type="text" id="native_name" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.native_name}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="English, Français..." required />
              <p v-if="errors.native_name" class="mt-1 text-sm text-red-600">{{ errors.native_name }}</p>
            </div>

            <div class="sm:col-span-2">
              <label for="code" class="block text-sm font-medium text-gray-700">Código ISO (2 letras)</label>
              <input v-model="form.code" type="text" id="code" maxlength="2" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.code}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm lowercase" placeholder="en" required />
              <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
            </div>

            <div class="sm:col-span-2">
              <label for="fallback_language" class="block text-sm font-medium text-gray-700">Idioma de Reserva</label>
              <input v-model="form.fallback_language" type="text" id="fallback_language" maxlength="2" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.fallback_language}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm lowercase" placeholder="es" />
              <p v-if="errors.fallback_language" class="mt-1 text-sm text-red-600">{{ errors.fallback_language }}</p>
            </div>

            <div class="sm:col-span-2">
              <label for="direction" class="block text-sm font-medium text-gray-700">Dirección</label>
              <select v-model="form.direction" id="direction" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.direction}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="ltr">Izquierda a Derecha (LTR)</option>
                <option value="rtl">Derecha a Izquierda (RTL)</option>
              </select>
              <p v-if="errors.direction" class="mt-1 text-sm text-red-600">{{ errors.direction }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado</h3>
          <div class="flex items-center">
            <button 
              type="button" 
              @click="form.active = !form.active"
              :class="form.active ? 'bg-indigo-600' : 'bg-gray-200'"
              class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
            >
              <span :class="form.active ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
            </button>
            <span class="ml-3 text-sm font-medium text-gray-900">Idioma Habilitado</span>
          </div>
        </div>
      </div>

      <div class="pt-5">
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.languages.index')" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
            Añadir Idioma
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApiClient from '@/api/client';

const breadcrumbItems = [
  { label: 'Idiomas', link: route('admin.languages.index') },
  { label: 'Crear' }
];

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
  name: '',
  native_name: '',
  code: '',
  fallback_language: '',
  direction: 'ltr',
  active: true,
});

const errors = ref({});
const processing = ref(false);


const submit = async() => {
  console.log('Datos que se envían:', JSON.stringify(form.value));
  processing.value = true;
  errors.value = {};
  try {
        await api.post('/api/v1/languages', form.value);
        router.visit(route('admin.languages.index'));
    } catch (e) {
      console.log('Error completo:', e.response?.data);
        errors.value = e.response?.status === 422
            ? e.response.data.errors
            : { general: 'Error inesperado.' };
    } finally {
        processing.value = false;
    }
};
</script>
