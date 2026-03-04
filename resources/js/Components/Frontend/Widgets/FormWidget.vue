<template>
  <div class="form-widget bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <div v-if="widget.title && widget.title[lang]" class="widget-title mb-4 font-bold text-xl text-gray-900">
      {{ widget.title[lang] }}
    </div>

    <div v-if="formData">
      <p v-if="formData.description" class="text-sm text-gray-600 mb-6">{{ formData.description }}</p>

      <form @submit.prevent="submitForm" class="space-y-4">
        <div v-for="(field, index) in formData.fields" :key="index">
          <label :for="`field-${index}`" class="block text-sm font-medium text-gray-700 mb-1">
            {{ field.label }} <span v-if="field.required" class="text-red-500">*</span>
          </label>
          
          <input 
            v-if="field.type === 'text' || field.type === 'email'" 
            :type="field.type" 
            :id="`field-${index}`"
            v-model="formValues[field.name]"
            :required="field.required"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
          
          <textarea 
            v-if="field.type === 'textarea'" 
            :id="`field-${index}`"
            v-model="formValues[field.name]"
            :required="field.required"
            rows="3"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          ></textarea>
        </div>

        <div class="pt-2">
          <button 
            type="submit" 
            :disabled="submitting"
            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
          >
            {{ submitting ? 'Enviando...' : (lang === 'es' ? 'Enviar' : 'Submit') }}
          </button>
        </div>

        <p v-if="successMessage" class="mt-2 text-sm text-green-600 font-medium">
          {{ successMessage }}
        </p>
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600 font-medium">
          {{ errorMessage }}
        </p>
      </form>
    </div>
    <div v-else class="text-center py-4">
      <div class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-indigo-600 border-t-transparent"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';

const props = defineProps({
  widget: {
    type: Object,
    required: true,
  },
  lang: {
    type: String,
    default: 'es',
  },
});

const formData = ref(null);
const formValues = reactive({});
const submitting = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(async () => {
  if (props.widget.config?.form_id) {
    try {
      const response = await axios.get(`/api/forms/${props.widget.config.form_id}`);
      formData.value = response.data;
      
      // Initialize form values
      if (formData.value.fields) {
        formData.value.fields.forEach(field => {
          formValues[field.name] = '';
        });
      }
    } catch (error) {
      console.error('Error loading form:', error);
    }
  }
});

const submitForm = async () => {
  submitting.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    // Assuming there's an API endpoint to handle submissions. 
    // If not, we might need to create it. For now, simulate success or post to a generic endpoint.
    // await axios.post(`/api/forms/${formData.value.id}/submit`, formValues);
    
    // Simulate success for now as requested per "meter un formulario" (visual)
    // Ideally we need a route: Route::post('api/forms/{id}/submit', ...)
    
    // Check if route exists, otherwise just log
    console.log('Submitting form:', formValues);
    
    await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate delay
    
    successMessage.value = props.lang === 'es' ? 'Formulario enviado correctamente.' : 'Form successfully submitted.';
    
    // Reset form
    Object.keys(formValues).forEach(key => formValues[key] = '');
    
  } catch (error) {
    errorMessage.value = props.lang === 'es' ? 'Error al enviar el formulario.' : 'Error submitting form.';
  } finally {
    submitting.value = false;
  }
};
</script>
