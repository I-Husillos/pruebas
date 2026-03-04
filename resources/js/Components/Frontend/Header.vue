<template>
    <header class="relative z-50 font-sans shadow-lg">
        <!-- Top Bar (Widgets & Utility) -->
        <div class="bg-slate-900 text-slate-300 text-xs py-2 relative z-50 transition-all duration-300 ease-in-out">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-10">
                <!-- Left: Widgets (Phone, Email, Socials) -->
                <div class="flex items-center gap-4 overflow-hidden">
                    <!-- Pass styling classes to WidgetZone to constrain it -->
                    <WidgetZone 
                        v-if="headerWidgets" 
                        zone-key="header" 
                        :widgets="headerWidgets" 
                        :lang="currentLang" 
                        :market="currentMarket"
                        class="flex gap-6 items-center" 
                    />
                    <span v-else class="opacity-50 italic">Global Presence</span>
                </div>

                <!-- Right: Market & Language -->
                <div class="flex items-center gap-4 shrink-0">
                    <div class="flex items-center gap-2">
                        <select 
                            :value="currentMarket" 
                            @change="$emit('change-market', $event.target.value)"
                            class="bg-slate-800 border-none rounded text-xs py-1 pl-2 pr-6 focus:ring-1 focus:ring-indigo-500 cursor-pointer hover:bg-slate-700 transition"
                        >
                            <option v-for="market in markets" :key="market.code" :value="market.code">
                                {{ market.name }}
                            </option>
                        </select>
                        <select 
                            :value="currentLang" 
                            @change="$emit('change-lang', $event.target.value)"
                            class="bg-slate-800 border-none rounded text-xs py-1 pl-2 pr-6 focus:ring-1 focus:ring-indigo-500 cursor-pointer hover:bg-slate-700 transition"
                        >
                            <option v-for="lang in languages" :key="lang.code" :value="lang.code">
                                {{ lang.native_name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 w-full z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('home', { market: currentMarket, lang: currentLang })" class="flex-shrink-0 flex items-center gap-2 group">
                            <!-- Logo Icon (Placeholder) -->
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:bg-indigo-700 transition-colors">
                                T
                            </div>
                            <span class="text-2xl font-bold text-gray-900 tracking-tight group-hover:text-indigo-600 transition-colors">
                                Termosalud
                            </span>
                        </Link>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden lg:flex lg:items-center lg:space-x-8">
                        <Link 
                            :href="route(`products.index.${currentLang}`, { market: currentMarket, lang: currentLang })"
                            class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors px-3 py-2 rounded-md hover:bg-gray-50"
                            :class="{ 'text-indigo-600 bg-indigo-50': route().current('products.*') }"
                        >
                            {{ translations['nav.products'] }}
                        </Link>
                        <Link 
                            :href="route(`treatments.index.${currentLang}`, { market: currentMarket, lang: currentLang })"
                            class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors px-3 py-2 rounded-md hover:bg-gray-50"
                            :class="{ 'text-indigo-600 bg-indigo-50': route().current('treatments.*') }"
                        >
                            {{ translations['nav.treatments'] }}
                        </Link>
                        
                        <!-- Contact Button -->
                        <a href="#contact" class="bg-indigo-600 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all">
                            Contactar
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center lg:hidden">
                        <button 
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        >
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger Icon -->
                            <svg v-if="!isMobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Close Icon -->
                            <svg v-else class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform -translate-y-2 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-2 opacity-0"
            >
                <div v-if="isMobileMenuOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full z-40">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <Link 
                            :href="route(`products.index.${currentLang}`, { market: currentMarket, lang: currentLang })"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                        >
                            {{ translations['nav.products'] }}
                        </Link>
                        <Link 
                            :href="route(`treatments.index.${currentLang}`, { market: currentMarket, lang: currentLang })"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50"
                        >
                            {{ translations['nav.treatments'] }}
                        </Link>
                    </div>
                    <!-- Mobile Widgets -->
                     <div class="p-4 border-t border-gray-100 bg-gray-50">
                         <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Widgets</h3>
                         <WidgetZone 
                            v-if="headerWidgets" 
                            zone-key="header_mobile" 
                            :widgets="headerWidgets" 
                            :lang="currentLang" 
                            :market="currentMarket"
                            class="space-y-2 text-sm text-gray-600" 
                        />
                     </div>
                </div>
            </transition>
        </nav>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import WidgetZone from '@/Components/Frontend/WidgetZone.vue';

const props = defineProps({
    currentMarket: String,
    currentLang: String,
    markets: Array,
    languages: Array,
    headerWidgets: Array,
});

const emit = defineEmits(['change-market', 'change-lang']);

const isMobileMenuOpen = ref(false);

const translations = computed(() => ({
    'nav.products': props.currentLang === 'es' ? 'Equipos' : 'Products',
    'nav.treatments': props.currentLang === 'es' ? 'Tratamientos' : 'Treatments',
}));
</script>
