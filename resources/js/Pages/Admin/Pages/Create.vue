<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="md:flex md:items-center md:justify-between mb-8">
      <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
          Crear Página
        </h2>
      </div>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Columna principal: localizaciones con tabs de mercado/idioma -->
      <div class="lg:col-span-2 space-y-8">
        <LocalizationTabs
          :markets="markets"
          v-model="form.localizations"
          :errors="errors"
          :forms="forms"
        />
      </div>

      <!-- Sidebar: metadatos de la tabla maestra `pages` -->
      <div class="space-y-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Publicación</h3>

          <div class="space-y-4">
            <!-- Estado -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Estado</label>
              <select
                v-model="form.status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <option value="draft">Borrador</option>
                <option value="published">Publicado</option>
                <option value="scheduled">Programado</option>
                <option value="pending_review">Pendiente de revisión</option>
              </select>
              <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
            </div>
          </div>
        </div>

        <!-- Error general -->
        <p v-if="errors.general" class="text-sm text-red-600">{{ errors.general }}</p>

        <!-- Botones -->
        <div class="flex justify-end gap-3 pt-4">
          <Link
            :href="route('admin.pages.index')"
            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="processing"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50 transition-colors"
          >
            {{ processing ? 'Guardando...' : 'Guardar Página' }}
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue'
import LocalizationTabs from '@/Components/Admin/LocalizationTabs.vue'
import ApiClient from '@/api/client'
import { usePageForm } from '@/Composables/Admin/usePageForm'

const props = defineProps({
  markets: { type: Array, required: true },
  forms: { type: Array, default: () => [] },
})

const breadcrumbItems = [
  { label: 'Páginas', link: route('admin.pages.index') },
  { label: 'Crear' },
]

const api = new ApiClient(usePage().props.apiToken)

const form = ref({
  status:        'draft',
  localizations: {},
})

const { errors, processing, submitCreate } = usePageForm({
  api,
  onSuccess: () => router.visit(route('admin.pages.index')),
})

const submit = () => submitCreate(form.value)
</script>
