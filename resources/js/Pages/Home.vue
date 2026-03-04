<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl">
                        {{ hero.title }}
                    </h1>
                    <p class="mx-auto mt-3 max-w-md text-base text-indigo-100 sm:text-lg md:mt-5 md:max-w-3xl md:text-xl">
                        {{ hero.subtitle }}
                    </p>
                    <div class="mx-auto mt-5 max-w-md sm:flex sm:justify-center md:mt-8">
                        <Link :href="route(`products.index.${lang}`, { market, lang })" 
                              class="inline-flex items-center rounded-md bg-white px-6 py-3 text-base font-medium text-indigo-600 shadow hover:bg-indigo-50">
                            {{ hero.cta }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                {{ lang === 'es' ? 'Productos Destacados' : 'Featured Products' }}
            </h2>
            
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="product in featuredProducts" :key="product.id" 
                     class="group relative overflow-hidden rounded-lg bg-white shadow hover:shadow-lg transition">
                    <div class="aspect-h-1 aspect-w-1 overflow-hidden bg-gray-200">
                        <img v-if="product.images && product.images[0]" 
                             :src="product.images[0].url" 
                             :alt="product.name?.[lang] || 'Product image'"
                             class="h-64 w-full object-cover object-center group-hover:opacity-75">
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ product.name?.[lang] || 'Sin nombre' }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ product.short_description?.[lang] || '' }}
                        </p>
                        <Link :href="route(`products.show.${lang}`, { market, lang, slug: product.slug?.[lang] || '#' })"
                              class="mt-4 inline-block text-indigo-600 hover:text-indigo-500">
                            {{ lang === 'es' ? 'Ver detalles →' : 'View details →' }}
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
import { computed } from 'vue';

const props = defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    featuredProducts: Array,
});

const hero = computed(() => {
    return props.lang === 'es' ? {
        title: 'Tecnología Médica de Vanguardia',
        subtitle: 'Equipos profesionales para medicina estética. Innovación, calidad y resultados excepcionales.',
        cta: 'Ver Productos',
    } : {
        title: 'Cutting-Edge Medical Technology',
        subtitle: 'Professional equipment for aesthetic medicine. Innovation, quality and exceptional results.',
        cta: 'View Products',
    };
});
</script>
