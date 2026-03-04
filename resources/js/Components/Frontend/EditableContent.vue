<template>
    <div class="relative group" :class="{ 'admin-editable': canEdit }">
        <!-- Main Content Slot -->
        <slot />

        <!-- Edit Overlay -->
        <div v-if="canEdit" class="absolute inset-0 border-2 border-indigo-500/0 group-hover:border-indigo-500/50 pointer-events-none transition-colors duration-200 rounded-lg"></div>
        
        <!-- Edit Button -->
        <Link v-if="canEdit" :href="editUrl" class="absolute top-2 right-2 bg-indigo-600 text-white p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transform scale-90 group-hover:scale-100 transition-all duration-200 z-10 hover:bg-indigo-500 pointer-events-auto" :title="label || 'Editar contenido'">
            <PencilIcon class="w-4 h-4" />
        </Link>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { PencilIcon } from 'lucide-vue-next';

const props = defineProps({
    editUrl: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: ''
    }
});

const page = usePage();
// Simplify logic: if user is logged in, they can see edit buttons (assuming admin logged in)
const canEdit = computed(() => !!page.props.auth.user);
</script>
