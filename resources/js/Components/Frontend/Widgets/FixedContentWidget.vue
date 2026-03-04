<template>
  <div class="fixed-content-widget">
    <div v-if="widget.title && widget.title[lang]" class="widget-title mb-4 font-semibold text-lg">
      {{ widget.title[lang] }}
    </div>
    <div class="content">
      <!-- Fixed content would be loaded based on content_key -->
      <p class="text-sm text-gray-600">{{ widget.config?.content_key }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  widget: {
    type: Object,
    required: true,
  },
  lang: {
    type: String,
    default: 'es',
  },
});
</script>
