<template>
    <div class="min-h-screen bg-gray-50 font-sans selection:bg-indigo-100 selection:text-indigo-700">
        <!-- New Header Component -->
        <Header 
            :current-market="currentMarket"
            :current-lang="currentLang"
            :markets="markets"
            :languages="languages"
            :header-widgets="headerWidgets"
            @change-market="changeMarket"
            @change-lang="changeLang"
        />

        <!-- Page Content -->
        <main class="relative z-0">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 mt-20 text-white pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                     <!-- Col 1: Brand -->
                     <div class="col-span-1 md:col-span-2">
                         <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                             <div class="w-8 h-8 bg-indigo-600 rounded flex items-center justify-center text-sm">T</div>
                             Termosalud
                         </h3>
                         <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                             Líderes en fabricación de equipos médico-estéticos. Innovación, calidad y servicio técnico propio desde España para el mundo.
                         </p>
                     </div>

                     <!-- Col 2: Widgets/Links -->
                     <div>
                         <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-6">Enlaces</h4>
                         <WidgetZone v-if="footerWidgets" zone-key="footer" :widgets="footerWidgets" :lang="currentLang" :market="currentMarket" class="space-y-3 text-sm text-gray-400" />
                     </div>
                     
                     <!-- Col 3: Contact/Legal -->
                     <div>
                         <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 mb-6">Legal</h4>
                         <ul class="space-y-3 text-sm text-gray-400">
                             <li><a href="#" class="hover:text-white transition">Aviso Legal</a></li>
                             <li><a href="#" class="hover:text-white transition">Privacidad</a></li>
                             <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                         </ul>
                     </div>
                 </div>
                 
                 <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                     <p class="text-xs text-gray-500">
                        &copy; {{ new Date().getFullYear() }} Termosalud S.L. Todos los derechos reservados.
                     </p>
                     <div class="flex gap-4">
                         <!-- Social Icons Placeholder -->
                     </div>
                 </div>
            </div>
        </footer>
        
        <!-- Admin Toolbar -->
        <AdminToolbar :edit-url="editUrl" />
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AdminToolbar from '@/Components/Frontend/AdminToolbar.vue';
import WidgetZone from '@/Components/Frontend/WidgetZone.vue';
import Header from '@/Components/Frontend/Header.vue';
import axios from 'axios';

const props = defineProps({
    currentMarket: String,
    currentLang: String,
    editUrl: String, 
    markets: {
        type: Array,
        default: () => [],
    },
    languages: {
        type: Array,
        default: () => [],
    },
});

const selectedMarket = ref(props.currentMarket);
const selectedLang = ref(props.currentLang);
const headerWidgets = ref([]);
const footerWidgets = ref([]);

// Only fetch widgets if not passed as props? 
// Ideally props should pass widgets to avoid client-side fetch flicker
// But sticking to current pattern for now.
onMounted(async () => {
    try {
        const [headerRes, footerRes] = await Promise.all([
            axios.get('/api/widgets/zone/header'),
            axios.get('/api/widgets/zone/footer')
        ]);
        headerWidgets.value = headerRes.data;
        footerWidgets.value = footerRes.data;
    } catch (error) {
        console.error('Error loading widgets:', error);
    }
});

const changeMarket = (val) => {
    selectedMarket.value = val;
    router.visit(route('home', { market: selectedMarket.value, lang: selectedLang.value }));
};

const changeLang = (val) => {
    selectedLang.value = val;
    router.visit(route('home', { market: selectedMarket.value, lang: selectedLang.value }));
};
</script>
