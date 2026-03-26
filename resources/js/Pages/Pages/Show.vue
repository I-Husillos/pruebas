<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :edit-url="editUrl">
        <Head>
            <title>{{ localization?.title || 'Página' }}</title>
            <meta name="description" :content="localization?.seo_metadata?.description || ''" />
        </Head>

        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <EditableContent :edit-url="editUrl" label="Editar página">
                <BlockRenderer :blocks="parsedContent" :lang="lang" />
            </EditableContent>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import EditableContent from '@/Components/Frontend/EditableContent.vue';
import { Head } from '@inertiajs/vue3';
import { useLocalizationFrontend } from '@/Composables/Frontend/useLocalizationFrontend';
import { toRef } from 'vue';
const props = defineProps({
    market: String,
    lang: String,
    page: Object,
    editUrl: String,
});

const { localization, parsedContent } = useLocalizationFrontend(
    toRef(props, 'page'),
    toRef(props, 'lang')
);
</script>
