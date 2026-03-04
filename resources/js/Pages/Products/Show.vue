<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages" :edit-url="editUrl">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <!-- Rest of content remains the same, just indented -->
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
                                <Link :href="route(`products.index.${lang}`, { market, lang })" class="text-gray-700 hover:text-indigo-600">
                                    {{ lang === 'es' ? 'Productos' : 'Products' }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                                </svg>
                                <span class="text-gray-500">{{ product.name?.[lang] || 'Sin nombre' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Product Detail -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
                    <!-- Product Images -->
                    <div class="aspect-h-4 aspect-w-3 overflow-hidden rounded-lg bg-gray-100">
                        <img v-if="product.images && product.images[0]" 
                             :src="product.images[0].url" 
                             :alt="product.name?.[lang] || 'Product image'"
                             class="h-full w-full object-cover object-center">
                    </div>

                    <!-- Product Info -->
                    <div class="mt-8 lg:mt-0">
                        <div v-if="product.category" class="mb-4">
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-4 py-1 text-sm font-medium text-indigo-800">
                                {{ product.category }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            {{ product.name?.[lang] || 'Sin nombre' }}
                        </h1>
                        
                        <p class="mt-4 text-lg text-gray-600">
                            {{ product.short_description?.[lang] || '' }}
                        </p>

                        <div class="prose prose-lg text-gray-600 mb-8" v-html="product.description?.[lang] || ''"></div>

                    <!-- Block Renderer for Flexible Content -->
                    <div v-if="product.blocks_json && product.blocks_json.length > 0" class="mt-12 border-t pt-12">
                         <BlockRenderer :blocks="product.blocks_json" />
                    </div>

                        <!-- Technical Specs -->
                        <div v-if="product.technical_specs" class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ lang === 'es' ? 'Especificaciones Técnicas' : 'Technical Specifications' }}
                            </h3>
                            <dl class="mt-4 space-y-2">
                                <div v-for="(value, key) in product.technical_specs" :key="key" 
                                     class="flex justify-between py-2 border-b border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">{{ key }}</dt>
                                    <dd class="text-sm text-gray-900">{{ value }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- CTA -->
                        <div class="mt-10">
                            <button type="button" 
                                    class="w-full rounded-md bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ lang === 'es' ? 'Solicitar Información' : 'Request Information' }}
                            </button>
                        </div>
                    </div>
                </div>

                
            </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    product: Object,
    editUrl: String,
});
</script>
