<template>
    <span 
        :class="badgeClass"
        class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
    >
        {{ badgeText }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: {
        type: [Boolean, Number, String],
        required: true,
    },
    trueText: {
        type: String,
        default: 'Activo',
    },
    falseText: {
        type: String,
        default: 'Inactivo',
    },
    trueClass: {
        type: String,
        default: 'bg-green-100 text-green-800',
    },
    falseClass: {
        type: String,
        default: 'bg-red-100 text-red-800',
    },
});

const badgeClass = computed(() => {
    return props.value ? props.trueClass : props.falseClass;
});

const badgeText = computed(() => {
    return props.value ? props.trueText : props.falseText;
});
</script>
