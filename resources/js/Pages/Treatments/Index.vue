<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                {{ lang === 'es' ? 'Nuestros Tratamientos' : 'Our Treatments' }}
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                {{ lang === 'es' 
                    ? 'Soluciones integrales para las necesidades estéticas más exigentes' 
                    : 'Comprehensive solutions for the most demanding aesthetic needs' }}
            </p>
            
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="treatment in treatments" :key="treatment.id" 
                     class="group relative overflow-hidden rounded-lg bg-white shadow-md hover:shadow-xl transition">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ treatment.name?.[lang] || 'Sin nombre' }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                            {{ treatment.short_description?.[lang] || '' }}
                        </p>
                        <!-- Link to treatment detail (if exists, currently just placeholder or anchor) -->
                        <div class="mt-4">
                             <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                {{ lang === 'es' ? 'Más información' : 'More info' }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="treatments.length === 0" class="mt-12 text-center text-gray-500">
                {{ lang === 'es' ? 'No hay tratamientos disponibles.' : 'No treatments available.' }}
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
    treatments: Array,
});
</script>
