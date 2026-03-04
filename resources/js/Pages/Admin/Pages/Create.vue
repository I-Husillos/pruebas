<template>
    <AdminLayout>
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Crear página</h2>
            </div>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-8 max-w-5xl bg-white p-6 rounded-lg shadow">
            
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Market -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Mercado</label>
                    <div class="mt-2">
                        <select v-model="form.market_code" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.market_code}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option v-for="m in markets" :key="m.code" :value="m.code">{{ m.name }} ({{ m.code }})</option>
                        </select>
                        <p v-if="form.errors.market_code" class="mt-1 text-sm text-red-600">{{ form.errors.market_code }}</p>
                    </div>
                </div>

                <!-- Language -->
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Idioma</label>
                    <div class="mt-2">
                        <select v-model="form.language_code" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.language_code}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option v-for="l in languages" :key="l.code" :value="l.code">{{ l.name }} ({{ l.code }})</option>
                        </select>
                         <p v-if="form.errors.language_code" class="mt-1 text-sm text-red-600">{{ form.errors.language_code }}</p>
                    </div>
                </div>

                <!-- Active -->
                <div class="sm:col-span-2 flex items-center pt-8">
                    <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                    <label class="ml-2 block text-sm font-medium leading-6 text-gray-900">Activa</label>
                </div>

                <!-- Slug -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Slug (URL)</label>
                    <div class="mt-2">
                        <input v-model="form.slug" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.slug}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <p class="mt-1 text-xs text-gray-500">Ej: promo-verano-2024</p>
                    </div>
                    <div v-if="form.errors.slug" class="mt-2 text-sm text-red-600">{{ form.errors.slug }}</div>
                </div>

                <!-- SEO Title -->
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium leading-6 text-gray-900">SEO Título</label>
                    <div class="mt-2">
                        <input v-model="form.seo_title" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.seo_title}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                         <p v-if="form.errors.seo_title" class="mt-1 text-sm text-red-600">{{ form.errors.seo_title }}</p>
                    </div>
                </div>

                <!-- SEO Description -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium leading-6 text-gray-900">SEO Descripción</label>
                    <div class="mt-2">
                        <textarea v-model="form.seo_description" rows="2" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.seo_description}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                         <p v-if="form.errors.seo_description" class="mt-1 text-sm text-red-600">{{ form.errors.seo_description }}</p>
                    </div>
                </div>

                <!-- Meta Keywords -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium leading-6 text-gray-900">Meta Keywords</label>
                    <div class="mt-2">
                        <input v-model="form.meta_keywords" type="text" :class="{'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.meta_keywords}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="palabra1, palabra2, palabra3" />
                        <p class="mt-1 text-xs text-gray-500">Palabras clave separadas por comas</p>
                         <p v-if="form.errors.meta_keywords" class="mt-1 text-sm text-red-600">{{ form.errors.meta_keywords }}</p>
                    </div>
                </div>
            </div>

            <!-- Page Builder -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Contenido de la Página (Bloques)</h3>
                <BlockEditor v-model="form.blocks_json" :forms="forms" />
                <input type="hidden" :value="JSON.stringify(form.blocks_json)"> <!-- Debug hidden -->
            </div>

            <div class="flex items-center justify-end gap-x-6 pt-6">
                <Link :href="route('admin.pages.index')" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</Link>
                <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import BlockEditor from '@/Components/Admin/BlockEditor.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    markets: Array,
    languages: Array,
    forms: Array,
});

const form = useForm({
    market_code: 'ES',
    language_code: 'es',
    slug: '',
    is_active: true,
    seo_title: '',
    seo_description: '',
    meta_keywords: '',
    blocks_json: [],
});

const submit = () => {
    form.post(route('admin.pages.store'));
};
</script>
