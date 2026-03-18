<!-- resources/js/Components/Admin/TranslationTabs.vue -->
<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900">Contenido Traducido</h3>
      <p class="mt-1 text-sm text-gray-500">
        Rellena el nombre y slug en cada idioma activo del sistema.
      </p>
    </div>

    <!--
      SELECTOR DE IDIOMA (dropdown)
      
      Motivo del cambio: con 20+ idiomas los botones en fila se vuelven
      ilegibles y rompen el layout. Un <select> es más compacto y accesible.

      - v-model="activeLang" cambia el idioma activo al seleccionar
      - La opción muestra un ✓ si ese idioma ya tiene título relleno
        (indicador visual de progreso sin necesitar los botones verdes)
    -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-1">Idioma</label>
      <select v-model="activeLang" class="block w-full rounded-md border-gray-300 shadow-sm
               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        <option v-for="lang in languages" :key="lang.code" :value="lang.code">
          <!--
            Mostramos ✓ si el idioma tiene título para que el admin
            sepa de un vistazo qué idiomas ya rellenó.
          -->
          {{ translations[lang.code]?.title?.trim() ? '✓ ' : '' }}{{ lang.name }}
        </option>
      </select>

      <!--
        Indicador de progreso: "X de Y idiomas completados"
        Ayuda al admin a saber cuántos le quedan sin abrir el dropdown.
      -->
      <p class="mt-1 text-xs text-gray-400">
        {{ completedCount }} de {{ languages.length }} idiomas con contenido
      </p>
    </div>

    <!-- Formulario del idioma activo -->
    <div v-if="activeLang && translations[activeLang]" class="space-y-6">

      <!--  Título  -->
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Nombre <span class="text-red-500">*</span>
          <span class="font-normal text-gray-400 text-xs ml-1">
            (en {{ currentLanguage?.name }})
          </span>
        </label>
        <input :value="translations[activeLang].title" @input="onTitleInput($event.target.value)" type="text" :class="hasError(`translations.${activeLang}.title`)
          ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
          : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
          class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
          :placeholder="`Nombre de la categoría en ${currentLanguage?.name}`" />
        <!--
          getError() hace el mapeo: busca primero "translations.es.title",
          si no encuentra, busca "translations.0.title" (índice numérico del backend).
          Así funcionan los errores tanto del frontend como del backend.
        -->
        <p v-if="hasError(`translations.${activeLang}.title`)" class="mt-1 text-sm text-red-600">
          {{ getError(`translations.${activeLang}.title`) }}
        </p>
      </div>

      <!--  Slug  -->
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Slug (URL) <span class="text-red-500">*</span>
        </label>
        <input :value="translations[activeLang].slug" @input="translations[activeLang].slug = $event.target.value"
          type="text" :class="hasError(`translations.${activeLang}.slug`)
            ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
          class="mt-1 block w-full rounded-md shadow-sm sm:text-sm" placeholder="nombre-de-la-categoria" />
        <p class="mt-1 text-xs text-gray-400">
          Se genera automáticamente desde el nombre. Debe ser único.
        </p>
        <p v-if="hasError(`translations.${activeLang}.slug`)" class="mt-1 text-sm text-red-600">
          {{ getError(`translations.${activeLang}.slug`) }}
        </p>
      </div>

      <!--  Descripción  -->
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Descripción <span class="text-red-500">*</span>
        </label>
        <textarea :value="translations[activeLang].description"
          @input="translations[activeLang].description = $event.target.value" rows="3" :class="hasError(`translations.${activeLang}.description`)
            ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
          class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
          :placeholder="`Descripción en ${currentLanguage?.name}`" />
        <p v-if="hasError(`translations.${activeLang}.description`)" class="mt-1 text-sm text-red-600">
          {{ getError(`translations.${activeLang}.description`) }}
        </p>
      </div>

      <!--  SEO  -->
      <!--
        Mostramos la sección SEO únicamente si el objeto seo_metadata
        existe en la traducción activa. Así el componente puede reutilizarse
        en entidades que no tengan SEO sin romper nada.
      -->
      <div v-if="translations[activeLang]?.seo_metadata !== undefined" class="border-t border-gray-200 pt-6 space-y-4">
        <h4 class="text-sm font-semibold text-gray-700">SEO</h4>

        <!-- Meta Title -->
        <div>
          <label class="block text-sm font-medium text-gray-700">
            Meta Title <span class="text-red-500">*</span>
            <span class="font-normal text-gray-400 text-xs ml-1">(máx. 60 caracteres)</span>
          </label>
          <input :value="translations[activeLang].seo_metadata?.title" @input="translations[activeLang].seo_metadata = {
            ...translations[activeLang].seo_metadata,
            title: $event.target.value
          }" type="text" maxlength="60" :class="hasError(`translations.${activeLang}.seo_metadata.title`)
              ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
              : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
            :placeholder="`Título SEO en ${currentLanguage?.name}`" />
          <p class="mt-1 text-xs text-gray-400">
            {{ (translations[activeLang].seo_metadata?.title || '').length }}/60 caracteres
          </p>
          <p v-if="hasError(`translations.${activeLang}.seo_metadata.title`)" class="mt-1 text-sm text-red-600">
            {{ getError(`translations.${activeLang}.seo_metadata.title`) }}
          </p>
        </div>

        <!-- Meta Description -->
        <div>
          <label class="block text-sm font-medium text-gray-700">
            Meta Description <span class="text-red-500">*</span>
            <span class="font-normal text-gray-400 text-xs ml-1">(máx. 1000 caracteres)</span>
          </label>
          <textarea :value="translations[activeLang].seo_metadata?.description" @input="translations[activeLang].seo_metadata = {
            ...translations[activeLang].seo_metadata,
            description: $event.target.value
          }" rows="2" maxlength="1000" :class="hasError(`translations.${activeLang}.seo_metadata.description`)
              ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
              : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
            :placeholder="`Descripción SEO en ${currentLanguage?.name}`" />
          <p class="mt-1 text-xs text-gray-400">
            {{ (translations[activeLang].seo_metadata?.description || '').length }}/1000 caracteres
          </p>
          <p v-if="hasError(`translations.${activeLang}.seo_metadata.description`)" class="mt-1 text-sm text-red-600">
            {{ getError(`translations.${activeLang}.seo_metadata.description`) }}
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { slugify, isSlugAutoGenerated } from '@/utils/slug';

const props = defineProps({
  languages: { type: Array, default: () => [] },
  modelValue: { type: Object, required: true },
  errors: { type: Object, default: () => ({}) },
  submittedOrder: { type: Array, default: () => [] },
});

const emit = defineEmits(['update:modelValue']);

//  Estado interno 
// El idioma activo comienza en el primero de la lista (normalmente el idioma
// principal configurado en el sistema).
const activeLang = ref(props.languages[0]?.code ?? 'es');

// Construimos el objeto de traducciones con campos vacíos para TODOS los idiomas.
// Luego sobreescribimos con los datos del padre (útil en modo edición).
const translations = ref(buildEmptyTranslations(props.languages));
Object.assign(translations.value, props.modelValue);

// Cuando cambia el estado interno, notificamos al padre (patrón v-model).
watch(translations, (newVal) => {
  emit('update:modelValue', newVal);
}, { deep: true, immediate: true });

//  Computed 

// Objeto completo del idioma activo (para mostrar su nombre en los labels)
const currentLanguage = computed(() =>
  props.languages.find(l => l.code === activeLang.value) ?? null
);

// Cuántos idiomas tienen título relleno (para el indicador de progreso)
const completedCount = computed(() =>
  props.languages.filter(l => translations.value[l.code]?.title?.trim()).length
);

//  Historial de slugs auto-generados 
// Guardamos el último slug que generamos automáticamente por idioma.
// Así podemos detectar si el admin lo modificó a mano y, en ese caso,
// NO sobreescribir su slug cuando siga escribiendo el título.
const slugifiedTitles = ref({});

//  Funciones 

function buildEmptyTranslations(languages) {
  const result = {};
  for (const lang of languages) {
    result[lang.code] = {
      language_id: lang.id,
      title: '',
      slug: '',
      description: '',
      seo_metadata: { title: '', description: '' },
    };
  }
  return result;
}

// Se llama cada vez que el admin escribe en el campo "Nombre".
// Actualiza el slug automáticamente, salvo que el admin lo haya editado a mano.
function onTitleInput(value) {
  translations.value[activeLang.value].title = value;

  const currentSlug = translations.value[activeLang.value].slug ?? '';
  const expectedSlug = slugifiedTitles.value[activeLang.value] ?? '';

  if (isSlugAutoGenerated(currentSlug, expectedSlug)) {
    const newSlug = slugify(value);
    translations.value[activeLang.value].slug = newSlug;
    slugifiedTitles.value[activeLang.value] = newSlug;
  }
}

//  Manejo de errores 
//
// El backend devuelve errores con índices numéricos:
//   "translations.0.title" → error del primer elemento del array enviado
//
// El componente trabaja con códigos de idioma:
//   "translations.es.title"
//
// getError() hace el puente: primero busca por código, luego por índice numérico.
// Así funciona tanto con errores del frontend (generados en buildMissingTranslationErrors)
// como con errores del backend (devueltos por Laravel tras la validación 422).

function hasError(field) {
  return !!getError(field);
}

function getError(field) {
  // 1. Búsqueda directa por código de idioma (errores del frontend)
  const directError = props.errors[field];
  if (directError) {
    return Array.isArray(directError) ? directError[0] : directError;
  }

  // 2. Búsqueda por índice numérico (errores del backend Laravel 422)
  const active = activeLang.value;
  const prefix = `translations.${active}.`;
  if (!active || !field.startsWith(prefix)) return '';

  const activeTranslation = translations.value[active];
  if (!activeTranslation) return '';

  // Usamos submittedOrder (el array filtrado que se envió al backend)
  // en lugar de todos los idiomas. Así el índice 0 del error del backend
  // corresponde al mismo idioma que el índice 0 del array enviado.
  const orderToUse = props.submittedOrder.length > 0
    ? props.submittedOrder
    : Object.values(translations.value).map(t => t.language_id);

  const index = orderToUse.findIndex(
    id => String(id) === String(activeTranslation.language_id)
  );

  if (index === -1) return '';

  const subField = field.slice(prefix.length);
  const wildcardError = props.errors[`translations.${index}.${subField}`];
  return Array.isArray(wildcardError) ? (wildcardError[0] ?? '') : (wildcardError ?? '');
}
</script>