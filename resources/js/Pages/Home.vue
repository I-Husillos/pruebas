<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <template v-if="contentType === 'page' && content">
                <h2 class="text-2xl font-semibold mb-2">
                    {{ getLocalization(content)?.title }}
                </h2>
                <BlockRenderer :blocks="parseContent(getLocalization(content)?.content)" :lang="lang" />
            </template>
            <template v-else-if="contentType === 'article' && content">
                <article>
                    <h1 class="text-3xl font-bold mb-4">
                        {{ getLocalization(content)?.title }}
                    </h1>
                    <p class="text-sm text-gray-500 mb-6">
                        {{ content.published_at }}
                    </p>
                    <BlockRenderer :blocks="parseContent(getLocalization(content)?.content)" :lang="lang" />
                </article>
            </template>
            <template v-else>
                <p class="text-gray-500">Contenido no encontrado.</p>
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
    content: Object,
    contentType: String,
});

console.log(props.languages, props.markets);


// Buscar la localización correspondiente al idioma actual
const localization = computed(() => {
    if (!props.content) return null;
    return props.content.localizations.find(
        loc => String(loc.language_id) === String(props.languages.find(l => l.code === props.lang)?.id)
    );
});

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
