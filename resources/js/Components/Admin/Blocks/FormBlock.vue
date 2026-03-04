<template>
    <div class="bg-white p-4 border rounded-md">
        <label class="block text-sm font-medium text-gray-700 mb-1">Selecciona un Formulario</label>
        <select v-model="selectedFormId" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="" disabled>-- Seleccionar --</option>
            <option v-for="form in forms" :key="form.id" :value="form.id">
                {{ form.name }} ({{ form.key }})
            </option>
        </select>
        <p class="mt-1 text-xs text-gray-500">El formulario seleccionado se incrustará en esta posición.</p>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: Object, // { form_id: null }
    forms: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);

const selectedFormId = ref(props.modelValue?.form_id || '');

watch(selectedFormId, (newVal) => {
    emit('update:modelValue', { form_id: newVal });
});
</script>
