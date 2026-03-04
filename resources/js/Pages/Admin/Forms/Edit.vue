<template>
    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Editar Formulario</h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-6 max-w-4xl bg-white p-6 rounded-lg shadow">
            
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Name -->
                <div class="sm:col-span-3">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nombre Interno</label>
                    <div class="mt-2">
                        <input v-model="form.name" type="text" id="name" :class="{'ring-red-300 focus:ring-red-600': form.errors.name, 'ring-gray-300 focus:ring-indigo-600': !form.errors.name}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                    </div>
                    <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                </div>

                <!-- Key -->
                <div class="sm:col-span-3">
                    <label for="key" class="block text-sm font-medium leading-6 text-gray-900">Clave Única (Slug)</label>
                    <div class="mt-2">
                        <input v-model="form.key" type="text" id="key" :class="{'ring-red-300 focus:ring-red-600': form.errors.key, 'ring-gray-300 focus:ring-indigo-600': !form.errors.key}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Ej: contact-es, newsletter-home</p>
                     <div v-if="form.errors.key" class="mt-2 text-sm text-red-600">{{ form.errors.key }}</div>
                </div>

                <!-- Recipient -->
                <div class="sm:col-span-4">
                    <label for="recipient_email" class="block text-sm font-medium leading-6 text-gray-900">Email Destinatario</label>
                    <div class="mt-2">
                        <input v-model="form.recipient_email" type="email" id="recipient_email" :class="{'ring-red-300 focus:ring-red-600': form.errors.recipient_email, 'ring-gray-300 focus:ring-indigo-600': !form.errors.recipient_email}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                    </div>
                    <div v-if="form.errors.recipient_email" class="mt-2 text-sm text-red-600">{{ form.errors.recipient_email }}</div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Campos del Formulario</h3>
                    <button type="button" @click="addField" class="rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100">
                        + Añadir Campo
                    </button>
                </div>

                <div class="space-y-4">
                    <div v-for="(field, index) in form.fields" :key="index" class="flex gap-4 items-start bg-gray-50 p-4 rounded-md">
                        <div class="flex-1 space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                            <div class="sm:w-1/4">
                                <label class="block text-xs font-medium text-gray-500">Nombre (campo)</label>
                                <input v-model="field.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>
                            <div class="sm:w-1/3">
                                <label class="block text-xs font-medium text-gray-500">Etiqueta Visible</label>
                                <input v-model="field.label" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>
                            <div class="sm:w-1/4">
                                <label class="block text-xs font-medium text-gray-500">Tipo</label>
                                <select v-model="field.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="text">Texto</option>
                                    <option value="email">Email</option>
                                    <option value="textarea">Área de Texto</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                            <div class="mt-6 flex items-center sm:mt-0 sm:pt-6">
                                <input v-model="field.required" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                <span class="ml-2 text-sm text-gray-500">Req.</span>
                            </div>
                        </div>
                        <button type="button" @click="removeField(index)" class="text-red-600 hover:text-red-900 mt-6">
                            <TrashIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 pt-6">
                <Link :href="route('admin.forms.index')" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</Link>
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    form: Object,
});

const form = useForm({
    name: props.form.name,
    key: props.form.key,
    recipient_email: props.form.recipient_email,
    fields: props.form.fields || [],
    active: props.form.active,
});

const addField = () => {
    form.fields.push({ name: '', label: '', type: 'text', required: false });
};

const removeField = (index) => {
    form.fields.splice(index, 1);
};

const submit = () => {
    form.put(route('admin.forms.update', props.form.id));
};
</script>
