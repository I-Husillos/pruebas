<template>
    <FrontendLayout :current-market="market" :current-lang="lang" :markets="markets" :languages="languages" :edit-url="editUrl">
        <Head>
            <title>{{ page.seoTitle || page.slug }}</title>
            <meta name="description" :content="page.seoDescription" />
        </Head>

        <EditableContent :edit-url="editUrl" label="Editar página">
            <div>
                <BlockRenderer :blocks="page.blocks || []" />
            </div>
        </EditableContent>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import BlockRenderer from '@/Components/BlockRenderer.vue';
import EditableContent from '@/Components/Frontend/EditableContent.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    market: String,
    lang: String,
    markets: Array,
    languages: Array,
    page: Object,
    editUrl: String,
});
</script>
