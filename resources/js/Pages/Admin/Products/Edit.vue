<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Editar Producto
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-8">
        <LocalizationTabs
          :markets="markets"
          v-model="form.localizations"
          :errors="errors"
          :on-delete-localization="deleteLocalization"
        />
      </div>

      <div class="space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Código</label>
              <input
                v-model="form.code"
                type="text"
                :class="{ 'border-red-300': errors.code }"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              />
              <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Categoría</label>
              <select
                v-model="form.product_category_id"
                :class="{ 'border-red-300': errors.product_category_id }"
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
            :href="route('admin.products.index')"
            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
          >
            {{ processing ? 'Guardando...' : 'Actualizar Producto' }}
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
import { buildInitialProductLocalizations } from '@/Composables/Admin/useProductLocalizations';
import { useProductForm } from '@/Composables/Admin/useProductForm';

const props = defineProps({
  product:    { type: Object, required: true },
  markets:    { type: Array,  required: true },
  categories: { type: Array,  default: () => [] },
});

const breadcrumbItems = [
  { label: 'Productos', link: route('admin.products.index') },
  { label: 'Editar' },
];

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
  product_category_id: props.product.product_category_id ?? null,
  code:                props.product.code ?? '',
  status:              props.product.status ?? 'draft',
  images:              props.product.images ?? [],
  related_treatments:  props.product.related_treatments ?? [],
  order:               props.product.order ?? 0,
  localizations:       buildInitialProductLocalizations(props.product.localizations ?? [], props.markets),
});

const { errors, processing, submitUpdate, removeLocalization } = useProductForm({
  api,
  onSuccess: () => router.visit(route('admin.products.index')),
});

const submit = () => submitUpdate(props.product.id, form.value);

const deleteLocalization = async (localizationId) => {
  if (!localizationId) return;
  const result = await removeLocalization(localizationId);
  if (!result.ok) {
    errors.value = { ...errors.value, general: result.message };
  }
};
</script>
