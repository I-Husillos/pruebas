<template>
    <AdminLayout>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <Link :href="route('admin.forms.index')" class="text-sm text-gray-500 hover:text-gray-700 mb-2 inline-block">← Volver</Link>
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Mensajes recibidos: {{ form.name }}</h1>
            </div>
        </div>

        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Fecha</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Datos</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="sub in submissions.data" :key="sub.id">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-0">
                                    {{ sub.created_at }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900">
                                    <div v-for="(val, key) in sub.data" :key="key" class="mb-1">
                                        <span class="font-medium text-gray-500 lowercase">{{ key }}:</span> {{ val }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ sub.ip_address }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <Pagination :data="submissions" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Admin/Pagination.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    form: Object,
    submissions: Object,
});
</script>
