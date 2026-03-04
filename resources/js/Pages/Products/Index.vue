<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                {{ lang === 'es' ? 'Nuestros Productos' : 'Our Products' }}
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                {{ lang === 'es' 
                    ? 'Equipos de última generación para medicina estética profesional' 
                    : 'State-of-the-art equipment for professional aesthetic medicine' }}
            </p>

            <!-- Category Filter -->
            <div v-if="categories && categories.length > 0" class="mt-8 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 overflow-x-auto">
                    <Link :href="route(`products.index.${lang}`, { market, lang })"
                          :class="[!selectedCategory ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium']">
                        {{ lang === 'es' ? 'Todos' : 'All' }}
                    </Link>
                    <Link v-for="category in categories" :key="category.id"
                          :href="route(`products.category.${lang}`, { market, lang, categorySlug: category.slug[lang] })"
                          :class="[selectedCategory?.id === category.id ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium']">
                        {{ category.name[lang] }}
                    </Link>
                </nav>
            </div>
            

            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="product in products" :key="product.id" 
                     class="group relative overflow-hidden rounded-lg bg-white shadow-md hover:shadow-xl transition">
                    <div class="aspect-h-1 aspect-w-1 overflow-hidden bg-gray-200">
                        <img v-if="product.images && product.images[0]" 
                             :src="product.images[0].url" 
                             :alt="product.name?.[lang] || 'Product image'"
                             class="h-72 w-full object-cover object-center group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-6">
                        <div v-if="product.category" class="mb-2">
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-0.5 text-xs font-medium text-indigo-800">
                                {{ product.category }}
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ product.name?.[lang] || 'Sin nombre' }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                            {{ product.short_description?.[lang] || '' }}
                        </p>
                        <Link :href="route(`products.show.${lang}`, { market, lang, slug: product.slug?.[lang] || '#' })"
                              class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
                            {{ lang === 'es' ? 'Ver detalles' : 'View details' }}
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    products: Array,
    categories: Array,
    selectedCategory: Object,
});
</script>
