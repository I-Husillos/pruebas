<template>
    <AdminLayout>
        <Breadcrumbs :items="[
            { label: 'Control de Cambios', route: route('admin.change-controls.index') },
            { label: 'Nueva Solicitud' }
        ]" />

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-9 text-gray-900">Nueva Solicitud</h1>
                <p class="mt-2 text-sm text-gray-700">Crea una nueva solicitud de cambio para el sistema.</p>
            </div>
        </div>

        <div class="mt-8 shadow ring-1 ring-gray-900/5 sm:rounded-lg bg-white p-8 max-w-4xl">
            <form @submit.prevent="submit" class="space-y-6">
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Título</label>
                    <div class="mt-2">
                        <input v-model="form.title" type="text" name="title" id="title" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required />
                    </div>
                    <p class="mt-1 text-sm text-red-600" v-if="form.errors.title">{{ form.errors.title }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Descripción</label>
                    <div class="mt-2">
                        <textarea v-model="form.description" id="description" name="description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required></textarea>
                    </div>
                    <p class="mt-1 text-sm text-red-600" v-if="form.errors.description">{{ form.errors.description }}</p>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium leading-6 text-gray-900">Razón / Justificación</label>
                    <div class="mt-2">
                        <textarea v-model="form.reason" id="reason" name="reason" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Explica por qué es necesario este cambio.</p>
                    <p class="mt-1 text-sm text-red-600" v-if="form.errors.reason">{{ form.errors.reason }}</p>
                </div>

                <!-- Impact -->
                <div>
                    <label for="impact" class="block text-sm font-medium leading-6 text-gray-900">Impacto Esperado</label>
                    <div class="mt-2">
                        <textarea v-model="form.impact" id="impact" name="impact" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">¿Qué áreas del sistema o negocio se verán afectadas?</p>
                    <p class="mt-1 text-sm text-red-600" v-if="form.errors.impact">{{ form.errors.impact }}</p>
                </div>

                <div class="flex justify-end gap-x-4 pt-4 border-t border-gray-100">
                    <Link :href="route('admin.change-controls.index')" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</Link>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50">
                        {{ form.processing ? 'Guardando...' : 'Guardar y Continuar' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    reason: '',
    impact: '',
    status: 'draft', // Default status
});

const submit = () => {
    form.post(route('admin.change-controls.store'));
};
</script>
