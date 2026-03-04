<template>
  <div v-if="widget.config" class="wysiwyg-widget prose max-w-none">
    <div v-if="widget.title && widget.title[lang]" class="widget-title mb-4 font-semibold text-lg">
      {{ widget.title[lang] }}
    </div>
    <div v-html="content"></div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  widget: {
    type: Object,
    required: true,
  },
  lang: {
    type: String,
    default: 'es',
  },
});

const content = computed(() => {
  return props.widget.config?.[`content_${props.lang}`] || props.widget.config?.content_es || '';
});
</script>
