<template>
    <div v-if="isValidUser" class="fixed bottom-0 left-0 w-full bg-gray-900 text-white z-50 shadow-lg px-4 py-3 flex justify-between items-center transition-transform duration-300">
        <div class="flex items-center space-x-4">
            <span class="font-bold text-sm tracking-wide text-indigo-400">TERMOSALUD ADMIN</span>
            <Link :href="route('admin.dashboard')" class="text-xs hover:text-white text-gray-300 transition-colors flex items-center gap-1">
                <LayoutDashboardIcon class="w-4 h-4" />
                Dashboard
            </Link>
        </div>
        
        <div class="flex items-center space-x-3">
             <Link v-if="editUrl" :href="editUrl" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs px-3 py-1.5 rounded-md flex items-center gap-2 transition-colors font-medium">
                <PencilIcon class="w-3.5 h-3.5" />
                Editar esta página
            </Link>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'; // Import computed directly
import { usePage, Link } from '@inertiajs/vue3';
import { PencilIcon, LayoutDashboard as LayoutDashboardIcon } from 'lucide-vue-next';

const props = defineProps({
    editUrl: {
        type: String,
        default: null
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Check if user exists and has a role that allows admin access (simplified for now to just existence or role check if available)
const isValidUser = computed(() => !!user.value);
</script>
