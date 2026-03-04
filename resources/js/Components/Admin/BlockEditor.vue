<template>
    <div class="space-y-6">
        <!-- List of Rows -->
        <div v-for="(row, rIndex) in rows" :key="rIndex" class="border-2 border-dashed border-gray-300 rounded-lg p-4 relative hover:border-indigo-300 transition-colors bg-gray-50/50">
            <!-- Row Controls -->
            <div class="absolute right-2 top-2 flex gap-2 opacity-50 hover:opacity-100 transition-opacity z-10">
                <select v-model="row.layout" class="text-xs border-gray-300 rounded shadow-sm py-1 pl-2 pr-6" @change="updateColumns(row)">
                    <option value="1">1 Columna</option>
                    <option value="1-1">2 Columnas (50/50)</option>
                    <option value="1-2">2 Columnas (33/66)</option>
                    <option value="2-1">2 Columnas (66/33)</option>
                    <option value="1-1-1">3 Columnas</option>
                </select>
                <button type="button" @click="moveRow(rIndex, -1)" v-if="rIndex > 0" class="bg-white p-1 rounded border shadow-sm text-gray-500 hover:text-indigo-600">↑</button>
                <button type="button" @click="moveRow(rIndex, 1)" v-if="rIndex < rows.length - 1" class="bg-white p-1 rounded border shadow-sm text-gray-500 hover:text-indigo-600">↓</button>
                <button type="button" @click="removeRow(rIndex)" class="bg-white p-1 rounded border shadow-sm text-red-400 hover:text-red-600">×</button>
            </div>

            <!-- Columns -->
            <div class="grid gap-4 mt-8" :class="getGridClass(row.layout)">
                <div v-for="(col, cIndex) in row.columns" :key="cIndex" class="bg-white border rounded-md p-3 min-h-[100px] flex flex-col gap-3">
                    <span class="text-xs text-gray-300 font-mono uppercase text-center block mb-2">Columna {{ cIndex + 1 }}</span>
                    
                    <!-- Blocks in Column -->
                    <div v-for="(block, bIndex) in col.blocks" :key="bIndex" class="relative group border rounded p-2 hover:shadow-sm">
                        <!-- Block Controls -->
                        <div class="absolute right-1 top-1 flex gap-1 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                             <button type="button" @click="moveBlock(col, bIndex, -1)" v-if="bIndex > 0" class="bg-white text-gray-400 hover:text-indigo-500 text-xs px-1 border rounded">↑</button>
                             <button type="button" @click="moveBlock(col, bIndex, 1)" v-if="bIndex < col.blocks.length - 1" class="bg-white text-gray-400 hover:text-indigo-500 text-xs px-1 border rounded">↓</button>
                             <button type="button" @click="removeBlock(col, bIndex)" class="bg-white text-red-400 hover:text-red-600 text-xs px-1 border rounded">×</button>
                        </div>
                        
                        <!-- Block Content -->
                        <div class="pt-4">
                            <span class="text-[10px] font-bold uppercase text-indigo-400 mb-1 block">{{ getBlockLabel(block.type) }}</span>
                            
                            <WysiwygBlock v-if="['rich_text', 'wysiwyg'].includes(block.type)" v-model="block.data" />
                            <MultimediaBlock v-if="block.type === 'multimedia'" v-model="block.data" />
                            <FormBlock v-if="block.type === 'form'" v-model="block.data" :forms="forms" />
                            
                            <!-- Legacy/Other types -->
                            <div v-if="block.type === 'html'">
                                <textarea v-model="block.data.code" rows="4" class="w-full bg-gray-900 text-white text-xs font-mono rounded p-2"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Add Block Button -->
                    <div class="mt-auto pt-2 border-t border-dashed border-gray-200">
                        <div class="flex flex-wrap gap-1 justify-center">
                            <button type="button" @click="addBlock(col, 'rich_text')" class="text-xs px-2 py-1 bg-gray-50 hover:bg-gray-100 border rounded flex items-center gap-1 text-gray-600">
                                <span>T</span> Texto
                            </button>
                            <button type="button" @click="addBlock(col, 'multimedia')" class="text-xs px-2 py-1 bg-gray-50 hover:bg-gray-100 border rounded flex items-center gap-1 text-gray-600">
                                <span>📷</span> Multimedia
                            </button>
                             <button type="button" @click="addBlock(col, 'form')" class="text-xs px-2 py-1 bg-gray-50 hover:bg-gray-100 border rounded flex items-center gap-1 text-gray-600">
                                <span>📝</span> Form
                            </button>
                             <button type="button" @click="addBlock(col, 'html')" class="text-xs px-2 py-1 bg-gray-50 hover:bg-gray-100 border rounded flex items-center gap-1 text-gray-600">
                                <span>&lt;/&gt;</span> HTML
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Row Button -->
        <div class="text-center py-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-all cursor-pointer" @click="addRow">
            <span class="text-indigo-600 font-medium">+ Añadir Fila (Sección)</span>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import WysiwygBlock from './Blocks/WysiwygBlock.vue';
import MultimediaBlock from './Blocks/MultimediaBlock.vue';
import FormBlock from './Blocks/FormBlock.vue';

const props = defineProps({
    modelValue: {
        type: Array, // Expected: [{ type: 'row', layout: '1-1', columns: [...] }]
        default: () => [],
    },
    forms: {
        type: Array,
        default: () => [],
    }
});

const emit = defineEmits(['update:modelValue']);

// Transform legacy flat blocks to rows if needed
const normalizeData = (data) => {
    if (!data || !Array.isArray(data)) return [];
    
    // Check if it's already in row format
    if (data.length > 0 && data[0].type === 'row') {
        return data; // Already migrated
    }

    // Migrate flat blocks to a single row
    if (data.length > 0) {
        return [{
            type: 'row',
            layout: '1',
            columns: [{
                blocks: data
            }]
        }];
    }

    return [];
};

const rows = ref(normalizeData(props.modelValue));

// Watch props deep? Or just on change?
// Careful with loops.
watch(() => props.modelValue, (newVal) => {
   // Only update if drastically different to avoid cursor jumps, 
   // but data structure usually mutated in place by v-model
   // Simplification: if empty and rows has data, don't overwrite?
   // Actually, just rely on internal state and emit updates.
});

watch(rows, (newVal) => {
    emit('update:modelValue', newVal);
}, { deep: true });

const addRow = () => {
    rows.value.push({
        type: 'row',
        layout: '1',
        columns: [{ blocks: [] }]
    });
};

const removeRow = (index) => {
    if (confirm('¿Eliminar fila completa?')) {
        rows.value.splice(index, 1);
    }
};

const moveRow = (index, dir) => {
    const item = rows.value.splice(index, 1)[0];
    rows.value.splice(index + dir, 0, item);
};

// Column logic
const updateColumns = (row) => {
    const layoutMap = {
        '1': 1,
        '1-1': 2,
        '1-2': 2,
        '2-1': 2,
        '1-1-1': 3
    };
    const targetCols = layoutMap[row.layout] || 1;
    
    while (row.columns.length < targetCols) {
        row.columns.push({ blocks: [] });
    }
    while (row.columns.length > targetCols) {
        // Move blocks to the last valid column before deleting?
        const removed = row.columns.pop();
        if (removed.blocks.length > 0) {
            row.columns[row.columns.length - 1].blocks.push(...removed.blocks);
        }
    }
};

const getGridClass = (layout) => {
    const classes = {
        '1': 'grid-cols-1',
        '1-1': 'grid-cols-1 md:grid-cols-2',
        '1-2': 'grid-cols-1 md:grid-cols-[1fr_2fr]',
        '2-1': 'grid-cols-1 md:grid-cols-[2fr_1fr]',
        '1-1-1': 'grid-cols-1 md:grid-cols-3'
    };
    return classes[layout] || 'grid-cols-1';
};

// Block logic
const addBlock = (col, type) => {
    let data = {};
    if (['rich_text', 'wysiwyg'].includes(type)) data = { content: '' };
    if (type === 'multimedia') data = { url: '', type: '', caption: '' };
    if (type === 'form') data = { form_id: null };
    if (type === 'html') data = { code: '' };
    
    col.blocks.push({
        type: type,
        data: data
    });
};

const removeBlock = (col, index) => {
    if (confirm('¿Eliminar bloque?')) {
        col.blocks.splice(index, 1);
    }
};

const moveBlock = (col, index, dir) => {
    const item = col.blocks.splice(index, 1)[0];
    col.blocks.splice(index + dir, 0, item);
};

const getBlockLabel = (type) => {
    const labels = {
        'rich_text': 'Texto',
        'wysiwyg': 'Texto',
        'multimedia': 'Multimedia',
        'form': 'Formulario',
        'html': 'HTML'
    };
    return labels[type] || type;
};
</script>

