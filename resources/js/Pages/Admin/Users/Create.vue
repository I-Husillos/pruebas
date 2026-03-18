<template>
    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Crear Usuario</h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-6 max-w-2xl bg-white p-6 rounded-lg shadow">

            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nombre</label>
                <div class="mt-2">
                    <input v-model="form.name" type="text" name="name" id="name" autocomplete="name"
                        :class="hasError('name') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600'"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="hasError('name')" class="mt-2 text-sm text-red-600">{{ getFieldError('name') }}</div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input v-model="form.email" type="email" name="email" id="email" autocomplete="email"
                        :class="hasError('email') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600'"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="hasError('email')" class="mt-2 text-sm text-red-600">{{ getFieldError('email') }}</div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Contraseña</label>
                <div class="mt-2">
                    <input v-model="form.password" type="password" name="password" id="password"
                        :class="hasError('password') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600'"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="hasError('password')" class="mt-2 text-sm text-red-600">{{ getFieldError('password') }}</div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirmar Contraseña</label>
                <div class="mt-2">
                    <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password_confirmation"
                        :class="hasError('password_confirmation') ? 'ring-red-300 focus:ring-red-600' : 'ring-gray-300 focus:ring-indigo-600'"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="hasError('password_confirmation')" class="mt-2 text-sm text-red-600">{{ getFieldError('password_confirmation') }}</div>
            </div>

            <fieldset>
                <legend class="text-sm font-semibold leading-6 text-gray-900">Roles</legend>
                <div class="mt-4 space-y-3">
                    <div v-for="role in roles" :key="role" class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input :value="role" v-model="form.roles" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label class="font-medium text-gray-900">{{ role }}</label>
                        </div>
                    </div>
                </div>
                <div v-if="hasError('roles')" class="mt-2 text-sm text-red-600">{{ getFieldError('roles') }}</div>
            </fieldset>

            <div v-if="hasError('general')" class="rounded-md bg-red-50 p-4">
                <p class="text-sm text-red-700">{{ getFieldError('general') }}</p>
            </div>

            <div class="flex items-center justify-end gap-x-6">
                <Link :href="route('admin.users.index')" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</Link>
                <button type="submit" :disabled="processing"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50">
                    {{ processing ? 'Guardando...' : 'Guardar' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ApiClient from '@/api/client';
import { useUserForm } from '@/Composables/Admin/useUserForm';
import { getErrorMessage, hasError as hasValidationError } from '@/utils/errors';

defineProps({
    roles: Array,
});

const api = new ApiClient(usePage().props.apiToken);

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [],
});

const { errors, processing, submitCreate } = useUserForm({
    api,
    onSuccess: () => router.visit(route('admin.users.index')),
});

const hasError = (field) => hasValidationError(errors.value, field);
const getFieldError = (field) => getErrorMessage(errors.value, field);

const submit = async () => {
    await submitCreate(form.value);
};
</script>