<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Editar Mercado: {{ market.name }}
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="space-y-8 divide-y divide-gray-200">
      <div class="space-y-6 sm:space-y-5">

        <!-- Información Geográfica -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Geográfica</h3>
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Mercado</label>
              <input v-model="form.name" type="text" id="name"
                :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.name}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="España, Global, etc." required />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <div class="sm:col-span-3">
              <label for="code" class="block text-sm font-medium text-gray-700">Código ISO (2 letras)</label>
              <input v-model="form.code" type="text" id="code" maxlength="2"
                :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.code}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase"
                placeholder="ES" required />
              <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
            </div>

            <div class="sm:col-span-3">
              <label for="region" class="block text-sm font-medium text-gray-700">Región</label>
              <select v-model="form.region" id="region"
                :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.region}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option v-for="region in regions" :key="region.code" :value="region.code">
                  {{ region.name }} 
                </option>
              </select>
              <p v-if="errors.region" class="mt-1 text-sm text-red-600">{{ errors.region }}</p>
            </div>
          </div>
        </div>

        <!-- Configuración de Idiomas -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Configuración de Idiomas</h3>
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

            <!-- Idioma principal: select dinámico -->
            <div class="sm:col-span-3">
              <label for="default_language" class="block text-sm font-medium text-gray-700">Idioma Principal</label>
              <select v-model="form.default_language" id="default_language"
                :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.default_language}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
                <option value="">Seleccionar idioma...</option>
                <option v-for="lang in languages" :key="lang.code" :value="lang.code">
                  {{ lang.native_name }} ({{ lang.code.toUpperCase() }})
                </option>
              </select>
              <p v-if="errors.default_language" class="mt-1 text-sm text-red-600">{{ errors.default_language }}</p>
            </div>

            <!-- Idiomas habilitados: checkboxes dinámicos -->
            <div class="sm:col-span-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Idiomas Habilitados</label>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <label v-for="lang in languages" :key="lang.code" class="inline-flex items-center">
                  <input type="checkbox"
                    v-model="form.enabled_languages"
                    :value="lang.code"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  <span class="ml-2 text-sm text-gray-600">
                    {{ lang.native_name }}
                    <span class="text-gray-400 text-xs">({{ lang.code.toUpperCase() }})</span>
                  </span>
                </label>
              </div>
              <p v-if="errors.enabled_languages" class="mt-1 text-sm text-red-600">{{ errors.enabled_languages }}</p>
            </div>
          </div>
        </div>

        <!-- Estado y Prioridad -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado y Prioridad</h3>
          <div class="flex items-center space-x-12">
            <div class="flex items-center">
              <button
                type="button"
                @click="form.active = !form.active"
                :class="form.active ? 'bg-indigo-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                <span
                  :class="form.active ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                </span>
              </button>
              <span class="ml-3 text-sm font-medium text-gray-900">Mercado Activo</span>
            </div>

            <div class="w-24">
              <label for="priority" class="block text-sm font-medium text-gray-700">Prioridad</label>
              <input v-model="form.priority" type="number" id="priority"
                :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': errors.priority}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
              <p v-if="errors.priority" class="mt-1 text-sm text-red-600">{{ errors.priority }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Error general -->
      <div v-if="errors.general" class="rounded-md bg-red-50 p-4">
        <p class="text-sm text-red-700">{{ errors.general }}</p>
      </div>

      <div class="pt-5">
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.markets.index')"
            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
            Cancelar
          </Link>
          <button type="submit" :disabled="processing"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors disabled:opacity-50">
            Actualizar Mercado
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApiClient from '@/api/client';

const props = defineProps({
  market:    { type: Object, required: true },
  languages: { type: Array,  default: () => [] },
  regions:   { type: Array,  default: () => [] },
});

const breadcrumbItems = [
  { label: 'Mercados', link: route('admin.markets.index') },
  { label: `Editar: ${props.market.name}` },
];

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
  name:              props.market.name,
  code:              props.market.code,
  region:            props.market.region,
  default_language:  props.market.default_language,
  enabled_languages: Array.isArray(props.market.enabled_languages)
                       ? props.market.enabled_languages
                       : ['es'],
  active:            Boolean(props.market.active ?? props.market.is_active ?? true),
  priority:          props.market.priority ?? 0,
});

const errors     = ref({});
const processing = ref(false);

const submit = async () => {
  processing.value = true;
  errors.value     = {};

  try {
    await api.put(`/api/v1/markets/${props.market.id}`, form.value);
    router.visit(route('admin.markets.index'));
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors ?? {};
    } else {
      errors.value = { general: 'Error inesperado al actualizar el mercado.' };
    }
  } finally {
    processing.value = false;
  }
};
</script>