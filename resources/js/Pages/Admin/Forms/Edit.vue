<template>
    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Editar Formulario
                </h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-6 max-w-4xl bg-white p-6 rounded-lg shadow">

            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <!-- Nombre interno -->
                <div class="sm:col-span-3">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">
                        Nombre Interno
                    </label>
                    <div class="mt-2">
                        <input v-model="form.name" type="text" id="name" :class="errors.name
                            ? 'ring-red-300 focus:ring-red-600'
                            : 'ring-gray-300 focus:ring-indigo-600'"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                    </div>
                    <div v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</div>
                </div>

                <!-- Clave única — solo lectura en edición -->

                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">
                        Clave Única (Slug)
                    </label>
                    <div class="mt-2">
                        <input :value="form.key" type="text" disabled
                            class="block w-full rounded-md border-0 py-1.5 text-gray-400 bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-200 sm:text-sm sm:leading-6 cursor-not-allowed" />
                    </div>
                    <p class="mt-1 text-xs text-gray-400">No editable — cambiarla rompería los bloques que la usan.</p>
                </div>

                <!-- Email destinatario -->
                <div class="sm:col-span-4">
                    <label for="recipient_email" class="block text-sm font-medium leading-6 text-gray-900">
                        Email Destinatario
                    </label>
                    <div class="mt-2">
                        <input v-model="form.recipient_email" type="email" id="recipient_email" :class="errors.recipient_email
                            ? 'ring-red-300 focus:ring-red-600'
                            : 'ring-gray-300 focus:ring-indigo-600'"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                    </div>
                    <div v-if="errors.recipient_email" class="mt-2 text-sm text-red-600">
                        {{ errors.recipient_email }}
                    </div>
                </div>

                <!-- Estado -->
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Estado</label>
                    <div class="mt-2 flex items-center">
                        <button
                            type="button"
                            @click="form.active = !form.active"
                            :class="form.active ? 'bg-indigo-600' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        >
                            <span
                                :class="form.active ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            />
                        </button>
                        <span class="ml-3 text-sm font-medium text-gray-900">{{ form.active ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                </div>

                <!-- Constructor de campos -->
                <div class="sm:col-span-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Campos del Formulario</h3>
                        <button type="button" @click="addField"
                            class="rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100">
                            + Añadir Campo
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div v-for="(field, index) in form.fields" :key="index"
                            class="flex gap-4 items-start bg-gray-50 p-4 rounded-md">
                            <div class="flex-1 space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                                <div class="sm:w-1/4">
                                    <label class="block text-xs font-medium text-gray-500">Nombre (campo)</label>
                                    <input v-model="field.name" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>
                                <div class="sm:w-1/3">
                                    <label class="block text-xs font-medium text-gray-500">Etiqueta Visible</label>
                                    <input v-model="field.label" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                </div>
                                <div class="sm:w-1/4">
                                    <label class="block text-xs font-medium text-gray-500">Tipo</label>
                                    <select v-model="field.type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="text">Texto</option>
                                        <option value="email">Email</option>
                                        <option value="tel">Teléfono</option>
                                        <option value="textarea">Área de Texto</option>
                                        <option value="select">Desplegable</option>
                                        <option value="checkbox">Checkbox</option>
                                    </select>
                                </div>
                                <div class="mt-6 flex items-center sm:mt-0 sm:pt-6">
                                    <input v-model="field.required" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                    <span class="ml-2 text-sm text-gray-500">Req.</span>
                                </div>
                            </div>
                            <button type="button" @click="removeField(index)"
                                class="text-red-400 hover:text-red-600 mt-6">
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <p v-if="errors.general" class="text-sm text-red-600">{{ errors.general }}</p>

            <div class="flex items-center justify-end gap-x-6 pt-6">
                <Link :href="route('admin.forms.index')" class="text-sm font-semibold leading-6 text-gray-900">
                    Cancelar
                </Link>
                <button type="submit" :disabled="processing"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 disabled:opacity-50">
                    {{ processing ? 'Guardando...' : 'Actualizar' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TrashIcon } from '@heroicons/vue/24/outline';
import ApiClient from '@/api/client';

const props = defineProps({
    form: { type: Object, required: true },
});

const api = new ApiClient(usePage().props.apiToken);

// Pre-cargamos los datos existentes del formulario.
// La key no se incluye porque no es editable.
const form = ref({
    name: props.form.name ?? '',
    key: props.form.key ?? '',
    recipient_email: props.form.recipient_email ?? '',
    active: props.form.is_active ?? true,
    fields: props.form.fields ?? [],
});

const errors = ref({});
const processing = ref(false);

const addField = () => {
    form.value.fields.push({ name: '', label: '', type: 'text', required: false });
};

const removeField = (index) => {
    form.value.fields.splice(index, 1);
};

const submit = async () => {
    processing.value = true;
    errors.value = {};

    try {
        await api.put(`/api/v1/forms/${props.form.id}`, form.value);
        router.visit(route('admin.forms.index'));
    } catch (e) {
        errors.value = e.response?.status === 422
            ? (e.response.data.errors || {})
            : { general: 'Error inesperado al actualizar.' };
    } finally {
        processing.value = false;
    }
};
</script>