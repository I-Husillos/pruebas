<template>
  <nav v-if="menuItems && menuItems.length > 0" :class="styleClasses">
    <div v-if="widget.title && widget.title[lang]" class="widget-title mb-4 font-semibold text-lg">
      {{ widget.title[lang] }}
    </div>
    <ul :class="isHorizontal ? 'flex space-x-6' : 'space-y-2'">
      <li v-for="item in menuItems" :key="item.id">
        <a 
          :href="getItemUrl(item)" 
          :target="item.target"
          class="block py-2 hover:text-indigo-600 transition-colors"
          :class="{ 'text-gray-700': !isHorizontal, 'text-gray-600': isHorizontal }"
        >
          <i v-if="item.icon" :class="item.icon" class="mr-2"></i>
          {{ item.label[lang] || item.label.es }}
        </a>
        
        <!-- Nested children -->
        <ul v-if="item.children && item.children.length > 0" :class="isHorizontal ? 'ml-4 mt-2 space-y-1' : 'ml-6 mt-2 space-y-1'">
          <li v-for="child in item.children" :key="child.id">
            <a 
              :href="getItemUrl(child)" 
              :target="child.target"
              class="block py-1 text-sm text-gray-600 hover:text-indigo-600 transition-colors"
            >
              {{ child.label[lang] || child.label.es }}
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  widget: {
    type: Object,
    required: true,
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

const menuItems = ref([]);

const isHorizontal = computed(() => props.widget.config?.style === 'horizontal');

const styleClasses = computed(() => {
  return isHorizontal.value ? 'menu-widget-horizontal' : 'menu-widget-vertical';
});

const getItemUrl = (item) => {
  if (item.url) {
    return item.url;
  }
  
  if (item.route_name) {
    try {
      const params = item.route_params || {};
      params.market = props.market;
      params.lang = props.lang;
      return route(item.route_name, params);
    } catch (e) {
      return '#';
    }
  }
  
  return '#';
};

onMounted(async () => {
  if (props.widget.config?.menu_id) {
    try {
      const response = await axios.get(`/api/menus/${props.widget.config.menu_id}/items`);
      menuItems.value = response.data;
    } catch (error) {
      console.error('Error loading menu items:', error);
    }
  }
});
</script>
