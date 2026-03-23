<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <template v-if="page && page.localizations && page.localizations.length">
                <div v-if="localization">
                    <h1 class="text-3xl font-bold mb-4">{{ localization.title }}</h1>
                    <p class="text-lg text-gray-600 mb-2" v-if="localization.excerpt">{{ localization.excerpt }}</p>
                    <div class="mb-4" v-if="localization.description">{{ localization.description }}</div>
                    <BlockRenderer v-if="localization.content" :blocks="contentBlocks(localization.content)" />
                </div>
                <div v-else class="text-center text-gray-400">No hay contenido para este idioma.</div>
            </template>
            <template v-else>
                <div class="text-center text-gray-400">{{ 'No hay contenido para esta página.' }}</div>
            </template>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import { computed } from 'vue';

const props = defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    page: Object,
});

// Buscar la localización correspondiente al idioma actual
const localization = computed(() => {
    if (!props.page || !props.page.localizations) return null;
    return props.page.localizations.find(
        loc => String(loc.language_id) === String(props.languages.find(l => l.code === props.lang)?.id)
    );
});


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
