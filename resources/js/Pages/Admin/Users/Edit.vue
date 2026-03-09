<template>
    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Editar Usuario</h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-6 max-w-2xl bg-white p-6 rounded-lg shadow">
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nombre</label>
                <div class="mt-2">
                    <input v-model="form.name" type="text" name="name" id="name" autocomplete="name" :class="{'ring-red-300 focus:ring-red-600': errors.name, 'ring-gray-300 focus:ring-indigo-600': !errors.name}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                <div class="mt-2">
                    <input v-model="form.email" type="email" name="email" id="email" autocomplete="email" :class="{'ring-red-300 focus:ring-red-600': errors.email, 'ring-gray-300 focus:ring-indigo-600': !errors.email}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Contraseña (Dejar en blanco para no cambiar)</label>
                <div class="mt-2">
                    <input v-model="form.password" type="password" name="password" id="password" :class="{'ring-red-300 focus:ring-red-600': errors.password, 'ring-gray-300 focus:ring-indigo-600': !errors.password}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6" />
                </div>
                <div v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password }}</div>
            </div>

            <!-- Confirm Password -->
            <div v-if="form.password">
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirmar Contraseña</label>
                <div class="mt-2">
                    <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
            </div>

            <!-- Roles -->
            <fieldset>
                <legend class="text-sm font-semibold leading-6 text-gray-900">Roles</legend>
                <div class="mt-4 space-y-3">
                    <div v-for="role in roles" :key="role" class="relative flex items-start">
                        <div class="flex h-6 items-center">
                            <input :value="role" v-model="form.roles" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label class="font-medium text-gray-900">{{ role }}</label>
                        </div>
                    </div>
                </div>
                <div v-if="errors.roles" class="mt-2 text-sm text-red-600">{{ errors.roles }}</div>
            </fieldset>

            <div class="flex items-center justify-end gap-x-6">
                <Link :href="route('admin.users.index')" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</Link>
                <button type="submit" :disabled="processing" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ApiClient from '@/api/client';

const props = defineProps({
    user: Object,
    roles: Array,
});

const { props: pageProps } = usePage();
const api = new ApiClient(pageProps.apiToken);

const form = ref({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    roles: props.user.roles ?? [],
});

const errors = ref({});
const processing = ref(false);

const submit = async () => {
    processing.value = true;
    errors.value = {};

    try {
        await api.put(`/api/v1/users/${props.user.id}`, form.value);
        router.visit(route('admin.users.index'));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors ?? {};
        } else {
            errors.value = { general: 'Error al actualizar el usuario.' };
        }
    } finally {
        processing.value = false;
    }
};
</script>
