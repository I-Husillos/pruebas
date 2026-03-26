<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :edit-url="editUrl">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li>
                        <Link :href="route('front', { market, lang })" class="text-gray-700 hover:text-indigo-600">
                            {{ lang === 'es' ? 'Inicio' : 'Home' }}
                        </Link>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                            </svg>
                            <span class="text-gray-500">{{ localization?.title || 'Sin nombre' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Cabecera: título + excerpt -->
            <div class="mb-10">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-6">
                    {{ localization?.title || 'Sin nombre' }}
                </h1>
                <p v-if="localization?.excerpt" class="mt-4 text-lg text-gray-600">
                    {{ localization.excerpt }}
                </p>
            </div>

            <!-- Descripción HTML si existe -->
            <div v-if="localization?.description"
                class="prose prose-lg text-gray-600 mb-10"
                v-html="localization.description">
            </div>

            <!-- Bloque de contenido flexible (ocupa todo el ancho) -->
            <div v-if="parsedContent && parsedContent.length > 0" class="mb-12">
                <BlockRenderer :blocks="parsedContent" :lang="lang" />
            </div>

        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import { Link } from '@inertiajs/vue3';
import { useLocalizationFrontend } from '@/Composables/Frontend/useLocalizationFrontend';
import { toRef } from 'vue';

const props = defineProps({
    market: String,
    lang: String,
    product: Object,
    editUrl: String,
});

const { localization, parsedContent } = useLocalizationFrontend(
    toRef(props, 'product'),
    toRef(props, 'lang')
);

</script>
