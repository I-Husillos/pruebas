<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta name="description" :content="seo.description" />
        <link rel="canonical" :href="seo.canonical" />

        <!-- WebMCP -->
        <meta name="webmcp-protocol" :content="webmcp.version" v-if="webmcp && webmcp.version" />
        <meta name="webmcp-context" :content="webmcp.context" v-if="webmcp && webmcp.context" />
        <meta name="webmcp-regulatory-scope" :content="webmcp.scope" v-if="webmcp && webmcp.scope" />
        <meta name="webmcp-content-status" :content="webmcp.status" v-if="webmcp && webmcp.status" />
        <meta name="webmcp-last-audit" :content="webmcp.last_audit" v-if="webmcp && webmcp.last_audit" />

        <!-- Hreflang alternates -->
        <template v-for="alt in seo.hreflangs || []" :key="alt.lang">
            <link rel="alternate" :hreflang="alt.lang" :href="alt.href" />
        </template>
    </Head>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
const props = defineProps({
    seo: Object,
    schema: Object,
    webmcp: Object,
})
</script>