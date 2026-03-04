<template>
    <div class="border rounded-md p-4 bg-white">
        <div v-if="!data.url" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors cursor-pointer" @click="triggerFileInput">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center">
                    <span class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Sube un archivo</span>
                    </span>
                    <p class="pl-1">o arrastra y suelta</p>
                </div>
                <p class="text-xs text-gray-500">Imágenes (JPG, PNG, GIF) o Videos (MP4, WEBM) hasta 50MB</p>
            </div>
            <input ref="fileInput" type="file" class="sr-only" @change="handleFileUpload" accept="image/*,video/*">
        </div>

        <div v-else class="relative group">
            <!-- Media Preview -->
            <div v-if="data.type === 'image'" class="relative aspect-w-16 aspect-h-9 bg-gray-100 rounded-md overflow-hidden">
                <img :src="data.url" class="object-cover w-full h-full" alt="Media content" />
            </div>
            <div v-else-if="data.type === 'video'" class="relative aspect-w-16 aspect-h-9 bg-gray-100 rounded-md overflow-hidden">
                <video :src="data.url" controls class="w-full h-full"></video>
            </div>

            <!-- Controls -->
            <div class="absolute top-2 right-2 flex gap-2">
                 <button type="button" @click="removeMedia" class="p-1 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-sm" title="Eliminar">
                    <TrashIcon class="w-4 h-4" />
                </button>
            </div>
            
            <!-- Metadata -->
            <div class="mt-2 space-y-2">
                 <input v-model="data.caption" type="text" placeholder="Pie de foto / Descripción (Alt)" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>

        <!-- Progress Bar -->
        <div v-if="uploading" class="mt-4">
            <div class="relative pt-1">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                    <div :style="`width: ${progress}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-300"></div>
                </div>
            </div>
            <p class="text-xs text-center text-gray-500">Subiendo... {{ progress }}%</p>
        </div>

        <div v-if="error" class="mt-2 text-sm text-red-600">
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { TrashIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

const props = defineProps({
    modelValue: Object, // { url: '', type: 'image'|'video', caption: '' }
});

const emit = defineEmits(['update:modelValue']);

// Local data copy
const data = ref(props.modelValue || { url: '', type: '', caption: '' });
const fileInput = ref(null);
const uploading = ref(false);
const progress = ref(0);
const error = ref(null);

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    error.value = null;
    uploading.value = true;
    progress.value = 0;

    const formData = new FormData();
    formData.append('file', file);

    try {
        const response = await axios.post(route('admin.media.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (progressEvent) => {
                progress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            },
        });

        // Success
        data.value = {
            url: response.data.url,
            type: response.data.type,
            caption: '',
        };
        emit('update:modelValue', data.value);

    } catch (err) {
        console.error(err);
        error.value = err.response?.data?.message || 'Error al subir el archivo.';
    } finally {
        uploading.value = false;
        // Reset input
        event.target.value = '';
    }
};

const removeMedia = () => {
    if (confirm('¿Eliminar este medio?')) {
        data.value = { url: '', type: '', caption: '' };
        emit('update:modelValue', data.value);
    }
};
</script>
