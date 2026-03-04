<template>
    <div class="diff-viewer border rounded-lg overflow-hidden">
        <div class="grid grid-cols-2 bg-gray-100 border-b p-2 font-semibold text-sm">
            <div class="text-gray-600 uppercase">Actual (Live)</div>
            <div class="text-indigo-600 uppercase">Propuesto (Draft)</div>
        </div>
        <div v-for="(value, key) in diffs" :key="key" class="grid grid-cols-2 border-b last:border-0 hover:bg-gray-50 transition-colors">
            <!-- Old Value -->
            <div class="p-3 border-r text-sm">
                <span class="block text-xs text-gray-400 font-mono mb-1">{{ key }} (Anterior)</span>
                <pre class="whitespace-pre-wrap font-mono text-xs text-red-600 bg-red-50 p-1 rounded">- {{ formatValue(original[key]) }}</pre>
            </div>

            <!-- New Value -->
            <div class="p-3 text-sm">
                <span class="block text-xs text-gray-400 font-mono mb-1">{{ key }} (Nuevo)</span>
                <pre class="whitespace-pre-wrap font-mono text-xs text-green-600 bg-green-50 p-1 rounded">+ {{ formatValue(proposed[key]) }}</pre>
            </div>
        </div>
        <div v-if="Object.keys(diffs).length === 0" class="p-4 text-center text-gray-500">
            No hay datos para comparar.
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    original: {
        type: Object,
        default: () => ({}),
    },
    proposed: {
        type: Object,
        default: () => ({}),
    }
});

const diffs = computed(() => {
    // Merge keys from both objects, but prioritize keys present in the 'proposed' payload 
    // since that's what we are actually interested in.
    const keys = new Set([...Object.keys(props.proposed || {})]);
    const result = {};
    
    keys.forEach(key => {
        // filter out internal fields
        if (['created_at', 'updated_at', 'deleted_at', 'id'].includes(key)) return;
        
        // Only include if it has actually changed
        if (hasChanged(key)) {
            result[key] = {
                old: props.original?.[key],
                new: props.proposed?.[key]
            };
        }
    });
    return result;
});

const hasChanged = (key) => {
    // Deep comparison for objects/arrays
    return JSON.stringify(props.original?.[key]) !== JSON.stringify(props.proposed?.[key]);
};

const formatValue = (val) => {
    if (val === null || val === undefined) return 'null';
    if (typeof val === 'object') return JSON.stringify(val, null, 2);
    if (typeof val === 'boolean') return val ? 'true' : 'false';
    return val;
};
</script>
