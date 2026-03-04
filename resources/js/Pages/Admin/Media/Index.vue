<template>
  <AdminLayout>
    <Breadcrumbs :items="breadcrumbItems" />

    <div class="py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Galería de Medios</h1>
            <p class="mt-2 text-sm text-gray-600">
              Todos los archivos multimedia subidos ({{ media.length }} archivos)
            </p>
          </div>
          
          <!-- Upload Button -->
          <button
            @click="showUploadModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Subir Archivos
          </button>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="filter = 'all'"
              :class="[
                filter === 'all'
                  ? 'border-indigo-500 text-indigo-600'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium'
              ]"
            >
              Todos ({{ media.length }})
            </button>
            <button
              @click="filter = 'image'"
              :class="[
                filter === 'image'
                  ? 'border-indigo-500 text-indigo-600'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium'
              ]"
            >
              Imágenes ({{ images.length }})
            </button>
            <button
              @click="filter = 'video'"
              :class="[
                filter === 'video'
                  ? 'border-indigo-500 text-indigo-600'
                  : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium'
              ]"
            >
              Videos ({{ videos.length }})
            </button>
          </nav>
        </div>

        <!-- Media Grid -->
        <div v-if="filteredMedia.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
          <div
            v-for="item in filteredMedia"
            :key="item.path"
            class="group relative aspect-square overflow-hidden rounded-lg bg-gray-100 shadow-sm hover:shadow-md transition-shadow"
          >
            <!-- Image Preview -->
            <img
              v-if="item.type === 'image'"
              :src="item.url"
              :alt="item.name"
              class="h-full w-full object-cover"
            />
            
            <!-- Video Preview -->
            <div v-else class="relative h-full w-full bg-gray-900 flex items-center justify-center">
              <svg class="h-16 w-16 text-white opacity-75" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
              </svg>
            </div>

            <!-- Overlay with actions -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
              <div class="flex space-x-2">
                <!-- Copy URL -->
                <button
                  @click="copyUrl(item.url)"
                  class="rounded-full bg-white p-2 text-gray-700 hover:bg-gray-100 transition"
                  title="Copiar URL"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                </button>

                <!-- View -->
                <a
                  :href="item.url"
                  target="_blank"
                  class="rounded-full bg-white p-2 text-gray-700 hover:bg-gray-100 transition"
                  title="Ver en nueva pestaña"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Info -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity">
              <p class="truncate font-medium">{{ item.name }}</p>
              <p class="text-gray-300">{{ formatSize(item.size) }}</p>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No hay archivos</h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ filter === 'all' ? 'No se han subido archivos multimedia.' : `No hay ${filter === 'image' ? 'imágenes' : 'videos'}.` }}
          </p>
        </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <div v-if="showUploadModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showUploadModal = false"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
              <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Subir Archivos Multimedia
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Selecciona imágenes (JPG, PNG, GIF, WebP) o videos (MP4, WebM) para subir.
                </p>
              </div>
            </div>
          </div>

          <!-- File Input Area -->
          <div class="mt-5">
            <div
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="handleDrop"
              :class="[
                isDragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300',
                'border-2 border-dashed rounded-lg p-8 text-center cursor-pointer hover:border-indigo-400 transition'
              ]"
              @click="$refs.fileInput.click()"
            >
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p class="mt-2 text-sm text-gray-600">
                <span class="font-medium text-indigo-600">Haz clic para seleccionar</span> o arrastra archivos aquí
              </p>
              <p class="mt-1 text-xs text-gray-500">
                Múltiples archivos permitidos
              </p>
            </div>
            <input
              ref="fileInput"
              type="file"
              multiple
              accept="image/*,video/*"
              class="hidden"
              @change="handleFileSelect"
            />
          </div>

          <!-- Selected Files -->
          <div v-if="selectedFiles.length > 0" class="mt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Archivos seleccionados:</h4>
            <ul class="space-y-2">
              <li v-for="(file, index) in selectedFiles" :key="index" class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded">
                <span class="truncate flex-1">{{ file.name }}</span>
                <button @click="removeFile(index)" class="ml-2 text-red-600 hover:text-red-800">
                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </li>
            </ul>
          </div>

          <!-- Upload Progress -->
          <div v-if="uploading" class="mt-4">
            <div class="flex items-center justify-between text-sm mb-1">
              <span class="text-gray-700">Subiendo...</span>
              <span class="text-gray-500">{{ uploadProgress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-indigo-600 h-2 rounded-full transition-all" :style="{ width: uploadProgress + '%' }"></div>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button
              type="button"
              @click="uploadFiles"
              :disabled="selectedFiles.length === 0 || uploading"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ uploading ? 'Subiendo...' : 'Subir' }}
            </button>
            <button
              type="button"
              @click="closeModal"
              :disabled="uploading"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  media: Array,
});

const breadcrumbItems = [
  { label: 'Galería de Medios' }
];

const filter = ref('all');
const showUploadModal = ref(false);
const selectedFiles = ref([]);
const uploading = ref(false);
const uploadProgress = ref(0);
const isDragging = ref(false);
const fileInput = ref(null);

const images = computed(() => props.media.filter(m => m.type === 'image'));
const videos = computed(() => props.media.filter(m => m.type === 'video'));

const filteredMedia = computed(() => {
  if (filter.value === 'all') return props.media;
  return props.media.filter(m => m.type === filter.value);
});

const formatSize = (bytes) => {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
};

const copyUrl = async (url) => {
  try {
    // Make URL absolute
    const absoluteUrl = url.startsWith('http') ? url : window.location.origin + url;
    await navigator.clipboard.writeText(absoluteUrl);
    alert('URL copiada al portapapeles');
  } catch (err) {
    console.error('Error al copiar:', err);
  }
};

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files);
  selectedFiles.value = [...selectedFiles.value, ...files];
};

const handleDrop = (event) => {
  isDragging.value = false;
  const files = Array.from(event.dataTransfer.files);
  selectedFiles.value = [...selectedFiles.value, ...files];
};

const removeFile = (index) => {
  selectedFiles.value.splice(index, 1);
};

const uploadFiles = async () => {
  if (selectedFiles.value.length === 0) return;

  uploading.value = true;
  uploadProgress.value = 0;

  try {
    const totalFiles = selectedFiles.value.length;
    let uploadedCount = 0;

    for (const file of selectedFiles.value) {
      const formData = new FormData();
      formData.append('file', file);

      await fetch(route('admin.media.store'), {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
      });

      uploadedCount++;
      uploadProgress.value = Math.round((uploadedCount / totalFiles) * 100);
    }

    // Reload the page to show new files
    router.reload({ only: ['media'] });
    
    closeModal();
  } catch (error) {
    console.error('Error uploading files:', error);
    alert('Error al subir archivos. Por favor, intenta de nuevo.');
  } finally {
    uploading.value = false;
    uploadProgress.value = 0;
  }
};

const closeModal = () => {
  if (!uploading.value) {
    showUploadModal.value = false;
    selectedFiles.value = [];
    isDragging.value = false;
  }
};
</script>
