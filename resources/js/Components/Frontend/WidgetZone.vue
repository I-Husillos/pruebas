<template>
  <div v-if="widgets && widgets.length > 0" class="widget-zone">
    <component 
      v-for="widget in widgets" 
      :key="widget.id"
      :is="getWidgetComponent(widget.type)"
      :widget="widget"
      :lang="lang"
      :market="market"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import MenuWidget from './Widgets/MenuWidget.vue';
import FormWidget from './Widgets/FormWidget.vue';
import WysiwygWidget from './Widgets/WysiwygWidget.vue';
import FixedContentWidget from './Widgets/FixedContentWidget.vue';

const props = defineProps({
  zoneKey: {
    type: String,
    required: true,
  },
  widgets: {
    type: Array,
    default: () => [],
  },
  lang: {
    type: String,
    default: 'es',
  },
  market: {
    type: String,
    default: 'es',
  },
});

const getWidgetComponent = (type) => {
  const components = {
    menu: MenuWidget,
    form: FormWidget,
    wysiwyg: WysiwygWidget,
    fixed_content: FixedContentWidget,
  };
  return components[type] || null;
};
</script>
