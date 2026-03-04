<template>
    <div class="space-y-12">
        <template v-for="(block, index) in blocks" :key="index">
            
            <!-- Row / Grid System -->
            <div v-if="block.type === 'row'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8" :class="getGridClass(block.layout)">
                    <div v-for="(col, cIndex) in block.columns" :key="cIndex" class="space-y-6">
                        <!-- Recursive Rendering for Column Blocks -->
                        <BlockRenderer :blocks="col.blocks" />
                    </div>
                </div>
            </div>

            <!-- Rich Text / WYSIWYG -->
            <div v-else-if="['rich_text', 'wysiwyg'].includes(block.type)" 
                 class="prose prose-lg mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-gray-500"
                 v-html="block.data.content">
            </div>

            <!-- Multimedia -->
            <div v-else-if="block.type === 'multimedia'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative rounded-xl overflow-hidden shadow-lg bg-gray-100">
                    <img v-if="block.data.type === 'image'" :src="block.data.url" class="w-full h-auto" :alt="block.data.caption">
                    <video v-if="block.data.type === 'video'" :src="block.data.url" class="w-full h-auto" controls></video>
                    
                    <div v-if="block.data.caption" class="absolute bottom-0 left-0 right-0 bg-black/50 p-4 text-white text-sm">
                        {{ block.data.caption }}
                    </div>
                </div>
            </div>

            <!-- Form Placeholder -->
            <div v-else-if="block.type === 'form'" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 text-center text-indigo-700">
                    [Form ID: {{ block.data.form_id }} - Rendering to be implemented]
                </div>
            </div>

            <!-- HTML -->
            <div v-else-if="block.type === 'html'" 
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 v-html="block.data.code">
            </div>

            <!-- Slider (Legacy/Specific) -->
            <div v-else-if="block.type === 'slider'" class="relative w-full overflow-hidden">
                <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide">
                    <div v-for="(slide, sIndex) in block.data.slides" :key="sIndex" 
                         class="w-full flex-shrink-0 snap-center relative h-[600px]">
                        <img :src="slide.image_url" class="w-full h-full object-cover" :alt="slide.title">
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <div class="text-center text-white px-4">
                                <h2 class="text-4xl font-bold mb-2">{{ slide.title }}</h2>
                                <p class="text-xl mb-6">{{ slide.subtitle }}</p>
                                <a v-if="slide.link" :href="slide.link" class="px-6 py-3 bg-white text-gray-900 rounded-full font-medium hover:bg-gray-100 transition">
                                    Ver más
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </template>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
// Recursive component needs no explicit import in <script setup> but helpful to check name
// Logic for grid classes
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

defineProps({
    blocks: {
        type: Array,
        default: () => [],
    }
});
</script>
