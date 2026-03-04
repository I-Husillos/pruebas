<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages" :edit-url="editUrl">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="flex mb-8" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                            <Link :href="route('home', { market, lang })" class="text-gray-700 hover:text-indigo-600">
                                {{ lang === 'es' ? 'Inicio' : 'Home' }}
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                                </svg>
                                <Link :href="route(`treatments.index.${lang}`, { market, lang })" class="text-gray-700 hover:text-indigo-600">
                                    {{ lang === 'es' ? 'Tratamientos' : 'Treatments' }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                                </svg>
                                <span class="text-gray-500">{{ treatment.name?.[lang] || 'Sin nombre' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Treatment Detail -->
                <div class="mb-12">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-6">
                        {{ treatment.name?.[lang] || 'Sin nombre' }}
                    </h1>
                    
                    <div class="prose prose-lg text-gray-600 mb-8" v-html="treatment.description?.[lang] || ''"></div>

                    <!-- Block Renderer for Flexible Content -->
                    <div class="mt-12 border-t pt-12">
                         <h2 class="text-2xl font-bold mb-6" v-if="treatment.blocks_json && treatment.blocks_json.length > 0">Contenido Detallado</h2>
                         <BlockRenderer :blocks="treatment.blocks_json" />
                    </div>
                </div>

                <!-- CTA -->
                <div class="bg-indigo-50 rounded-xl p-8 text-center mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ lang === 'es' ? '¿Interesado en este tratamiento?' : 'Interested in this treatment?' }}</h2>
                    <p class="text-lg text-gray-600 mb-6">{{ lang === 'es' ? 'Contáctanos para más información o para encontrar un centro cercano.' : 'Contact us for more information or to find a nearby center.' }}</p>
                    <button type="button" 
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white hover:bg-indigo-700">
                        {{ lang === 'es' ? 'Contactar' : 'Contact Us' }}
                    </button>
                </div>
            </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    treatment: Object,
    editUrl: String,
});
</script>
