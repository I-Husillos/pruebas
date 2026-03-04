<template>
  <draggable 
    :list="items" 
    :group="{ name: 'g1' }" 
    item-key="id"
    class="space-y-2 min-h-[10px]"
    handle=".drag-handle"
    @end="$emit('change')"
    :component-data="{
      tag: 'div',
      name: 'flip-list',
      type: 'transition'
    }"
  >
    <template #item="{ element }">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Item Header -->
        <div class="p-3 hover:bg-gray-50 transition-colors flex items-center justify-between group">
          <div class="flex items-center gap-3 flex-1">
            <!-- Drag Handle -->
            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 p-1">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
            </div>
            
            <!-- Icon -->
            <div v-if="element.icon" class="flex-shrink-0 text-gray-500 w-6 text-center">
              <i :class="element.icon"></i>
            </div>
            
            <!-- Info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <span class="font-medium text-gray-900 text-sm">
                  {{ element.label?.es || element.label?.en || 'Sin título' }}
                </span>
                <span v-if="element.target === '_blank'" class="text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">
                  Nueva pestaña
                </span>
              </div>
              <div class="text-xs text-gray-400 font-mono truncate max-w-md">
                {{ getItemUrl(element) }}
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <span :class="element.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium">
              {{ element.active ? 'Activo' : 'Inactivo' }}
            </span>
            
            <Link :href="route('admin.menu-items.edit', element.id)" class="p-1.5 text-gray-400 hover:text-indigo-600" title="Editar">
              <PencilSquareIcon class="h-4 w-4" />
            </Link>
            
            <button @click="$emit('delete', element.id)" class="p-1.5 text-gray-400 hover:text-red-600" title="Eliminar">
              <TrashIcon class="h-4 w-4" />
            </button>
          </div>
        </div>

        <!-- Nested Children -->
        <div class="pl-8 pr-2 pb-2 bg-gray-50/50 border-t border-gray-100">
           <!-- Droppable area for children -->
           <draggable 
              v-if="!element.children || element.children.length === 0"
              :list="[]"
              :group="{ name: 'g1' }" 
              item-key="id"
              class="min-h-[5px] py-1"
              @change="(evt) => onAddChild(evt, element)"
           >
              <template #item="{ element: child }"></template>
           </draggable>
            
          <MenuItemList 
            v-if="element.children && element.children.length > 0" 
            :items="element.children" 
            @change="$emit('change')"
            @delete="$emit('delete', $event)"
          />
        </div>
      </div>
    </template>
  </draggable>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import draggable from 'vuedraggable';
import { ref } from 'vue';

const props = defineProps({
  items: Array
});

const emit = defineEmits(['change', 'delete']);

const getItemUrl = (item) => {
  if (item.url) return item.url;
  if (item.route_name) return `route: ${item.route_name}`;
  return '#';
};

const onAddChild = (evt, parentItem) => {
    if (evt.added) {
        if (!parentItem.children) parentItem.children = [];
        parentItem.children.push(evt.added.element);
        // The item is removed from the old list automatically by vuedraggable
        emit('change');
    }
}
</script>
