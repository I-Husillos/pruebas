<template>
    <div class="bg-white">
        <Wysiwyg v-model="content" />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Wysiwyg from '@/Components/Wysiwyg.vue';

const props = defineProps({
    modelValue: Object, // { content: '' }
});

const emit = defineEmits(['update:modelValue']);

const content = ref(props.modelValue?.content || '');

watch(content, (newVal) => {
    emit('update:modelValue', { ...props.modelValue, content: newVal });
});
</script>
