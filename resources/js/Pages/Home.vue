<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <template v-if="pages && pages.length">
                <div v-for="page in pages" :key="page.id" class="mb-12">
                    <template v-if="page.name === 'about-us'">
                        <h2 class="text-2xl font-semibold mb-2">Sobre Nosotros</h2>
                        <BlockRenderer :blocks="parseContent(getLocalization(page)?.content)" :lang="lang" />
                    </template>
                    <template v-else-if="page.name === 'contact'">
                        <h2 class="text-2xl font-semibold mb-2">Contacto</h2>
                        <BlockRenderer :blocks="parseContent(getLocalization(page)?.content)" :lang="lang" />
                    </template>
                    <template v-else>
                        <h2 class="text-2xl font-semibold mb-2">{{ getLocalization(page)?.title }}</h2>
                        <BlockRenderer :blocks="parseContent(getLocalization(page)?.content)" :lang="lang" />
                    </template>
                </div>
            </template>
            <template v-else>
                <div class="text-center text-gray-400">No hay páginas para este mercado e idioma.</div>
            </template>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import { computed, watch } from 'vue';

const props = defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    pages: Array,
});

// Forzar actualización cuando cambian las props relevantes
watch(() => [props.pages, props.market, props.lang], () => {
    // No es necesario hacer nada aquí si todo depende de props,
    // pero este watcher fuerza el refresco del componente si Inertia lo reutiliza
}, { deep: true });


// Devuelve la localización de la página para el idioma actual
function getLocalization(page) {
    if (!page || !page.localizations) return null;
    const langObj = props.languages.find(l => l.code === props.lang);
    if (!langObj) return page.localizations[0] || null;
    return page.localizations.find(loc => String(loc.language_id) === String(langObj.id)) || page.localizations[0] || null;
}


const parseContent = (content) => {
    if (!content) {
        return null;
    }

    if (Array.isArray(content) || typeof content === 'object') {
        return content;
    }

    if (typeof content === 'string') {
        try {
            return JSON.parse(content);
        } catch {
            return null;
        }
    }

    return null;
};

const contentBlocks = (content) => {
    const parsed = parseContent(content);

    if (!parsed) return [];

    if (Array.isArray(parsed)) {
        return parsed;
    }

    return [];
};

const renderHtmlContent = (content) => {
    if (!content) return '';

    if (typeof content === 'string') {
        return content;
    }

    const parsed = parseContent(content);

    if (!parsed) return '';
    if (typeof parsed === 'string') return parsed;

    return '';
};
</script>
