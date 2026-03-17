<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900">Contenido Localizado</h3>
      <p class="mt-1 text-sm text-gray-500">
        Selecciona un mercado y luego el idioma.
      </p>
    </div>

    <!--  NIVEL 1: Pestañas de MERCADO  -->
    <!--
      Iteramos sobre props.markets. Si el backend añade un mercado nuevo,
      aparece aquí automáticamente sin tocar este componente.
    -->
    <div class="flex flex-wrap gap-2 pb-4 mb-4 border-b border-gray-200">
      <button v-for="market in markets" :key="market.code" type="button" @click="selectMarket(market.code)" :class="activeMarket === market.code
        ? 'bg-indigo-600 text-white shadow-sm'
        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
        class="px-3 py-2 rounded-lg text-sm font-semibold transition-all flex items-center gap-2">

        <span>{{ market.name }}</span>
        <!-- Badge con la región regulatoria: EU_MDR, FDA, LATAM_GENERIC... -->
        <span class="hidden sm:inline text-xs font-normal opacity-70">{{ market.region }}</span>
        <!-- Punto verde si algún idioma de este mercado tiene contenido -->
        <span v-if="marketHasContent(market.code)" class="w-2 h-2 rounded-full bg-green-400 flex-shrink-0"
          title="Tiene contenido" />
      </button>
    </div>

    <!--  NIVEL 2: Pestañas de IDIOMA  -->
    <!--
      Solo los idiomas del mercado activo. El orden respeta is_default:
      el idioma principal del mercado aparece primero.
    -->
    <div v-if="currentMarket">
      <div class="flex flex-wrap gap-2 mb-6">
        <button v-for="lang in currentMarket.languages" :key="lang.code" type="button"
          @click="activeLanguage = lang.code" :class="activeLanguage === lang.code
            ? 'bg-indigo-100 text-indigo-700 ring-2 ring-indigo-400'
            : 'bg-gray-50 text-gray-500 hover:bg-gray-100 ring-1 ring-gray-200'"
          class="px-3 py-1.5 rounded-md text-xs font-bold transition-all flex items-center gap-1.5">
          <span class="uppercase font-mono">{{ lang.code }}</span>
          <span class="font-normal normal-case">{{ lang.name }}</span>
          <span v-if="lang.is_default" class="text-indigo-400 font-normal">·default</span>
          <!-- Punto verde si esta combinación específica tiene título relleno -->
          <!-- <span v-if="hasContent(activeMarket, lang.code)"
            class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0" /> -->

          <!-- ✕ solo visible si:
              - estamos en edición (la prop existe)
              - esta localización YA está en BD (tiene id)-->
          <span
              v-if="onDeleteLocalization
                    && localizations[buildLocalizationKey(activeMarket, lang.code)]?.id"
              @click.stop="onDeleteLocalization(
                  localizations[buildLocalizationKey(activeMarket, lang.code)].id
              )"
              class="ml-2 text-red-400 hover:text-red-600 text-xs font-bold leading-none"
              title="Eliminar esta localización"> ✕ </span>
        </button>
      </div>

      <!--  ÁREA DE CONTENIDO: campos para la combinación activa  -->
      <div v-if="activeKey && localizations[activeKey]">

        <!-- Banner informativo: recuerda al admin para qué normativa está escribiendo -->
        <div
          class="flex items-center gap-2 rounded-lg bg-blue-50 border border-blue-100 px-4 py-2.5 text-sm text-blue-700 mb-6">
          <span>
            Redactando para
            <strong>{{ currentMarket.name }}</strong>
            en <strong>{{ currentLanguage?.name }}</strong>
            — normativa <strong class="uppercase">{{ currentMarket.region }}</strong>
          </span>
        </div>

        <div class="space-y-6">

          <!-- TÍTULO ─ -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Título <span class="text-red-500">*</span>
            </label>
            <input :value="localizations[activeKey].title" @input="handleTitleInput($event.target.value)" type="text"
              :class="hasError(`localizations.${activeKey}.title`)
                ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
              class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
              :placeholder="`Título en ${currentLanguage?.name}`" />
            <p v-if="hasError(`localizations.${activeKey}.title`)" class="mt-1 text-sm text-red-600">
              {{ getError(`localizations.${activeKey}.title`) }}
            </p>
          </div>

          <!-- SLUG (URL) ─ -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Slug (URL)</label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <!-- Prefijo que muestra visualmente cómo quedará la URL pública -->
              <span
                class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-gray-400 text-xs whitespace-nowrap">
                /{{ activeMarket }}/{{ activeLanguage }}/…/
              </span>
              <input :value="localizations[activeKey].slug" @input="localizations[activeKey].slug = $event.target.value"
                type="text"
                class="block w-full min-w-0 flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="mi-articulo" />
            </div>
            <p class="mt-1 text-xs text-gray-400">
              Se genera automáticamente desde el título. Editable manualmente. Debe ser único en toda la plataforma.
            </p>
            <p v-if="hasError(`localizations.${activeKey}.slug`)" class="mt-1 text-sm text-red-600">
              {{ getError(`localizations.${activeKey}.slug`) }}
            </p>
          </div>

          <!--
            Estos campos se guardan en la columna seo_metadata (JSON) de la tabla
            de localizaciones. Son específicos por mercado+idioma porque el contenido
            SEO puede variar regulatoriamente entre mercados (ej: España EU_MDR vs USA FDA).
          -->
          <div class="border-t border-gray-200 pt-6 space-y-4">
            <div>
              <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                SEO
                <span class="font-normal text-gray-400 text-xs">
                  (específico para {{ currentMarket?.name }} · {{ currentLanguage?.name }})
                </span>
              </h4>
            </div>

            <!-- SEO Title -->
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Meta Title
                <span class="font-normal text-gray-400 text-xs ml-1">
                  (aparece en Google y en la pestaña del navegador)
                </span>
              </label>
              <input :value="localizations[activeKey].seo_metadata?.title" @input="localizations[activeKey].seo_metadata = {
                ...localizations[activeKey].seo_metadata,
                title: $event.target.value
              }" type="text" maxlength="60"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :placeholder="`Título SEO en ${currentLanguage?.name} (máx. 60 caracteres)`" />
              <p class="mt-1 text-xs text-gray-400">
                {{ (localizations[activeKey].seo_metadata?.title || '').length }}/60 caracteres
              </p>
            </div>

            <!-- SEO Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Meta Description
                <span class="font-normal text-gray-400 text-xs ml-1">
                  (resumen que muestra Google en los resultados de búsqueda)
                </span>
              </label>
              <textarea :value="localizations[activeKey].seo_metadata?.description" @input="localizations[activeKey].seo_metadata = {
                ...localizations[activeKey].seo_metadata,
                description: $event.target.value
              }" rows="2" maxlength="160"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :placeholder="`Descripción SEO en ${currentLanguage?.name} (máx. 160 caracteres)`" />
              <p class="mt-1 text-xs text-gray-400">
                {{ (localizations[activeKey].seo_metadata?.description || '').length }}/160 caracteres
              </p>
            </div>
          </div>

          <!-- CONTENIDO WYSIWYG (Page Builder) -->
          <div class="border-t border-gray-200 pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-4">
              Contenido de la Página
              <span class="font-normal text-gray-400 text-xs ml-1">
                (bloques específicos para {{ currentMarket.name }} · {{ currentLanguage?.name }})
              </span>
            </label>
            <BlockEditor :key="activeKey" v-model="localizations[activeKey].content" :forms="forms" />
          </div>
        </div>
      </div>
    </div>

    <!-- Estado vacío: no hay mercados configurados -->
    <div v-else class="text-center py-10 text-gray-400">
      <p class="font-medium">No hay mercados activos.</p>
      <p class="text-sm mt-1">
        Configúralos en
        <a :href="route('admin.markets.index')" class="text-indigo-500 underline">Gestión de Mercados</a>.
      </p>
    </div>

  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useLocalizations } from '@/Composables/useLocalizations';
import { buildLocalizationKey } from '@/utils/localizations';
import BlockEditor from '@/Components/Admin/BlockEditor.vue';


// Props 
const props = defineProps({
  // Array de mercados activos con sus idiomas (viene del controlador backend)
  markets: {
    type: Array,
    required: true,
  },
  // El objeto de localizaciones completo (v-model)
  modelValue: {
    type: Object,
    required: true,
  },
  // Errores de validación del backend, con claves como "localizations.es_es.title"
  errors: {
    type: Object,
    default: () => ({}),
  },
  forms: {
    type: Array, default: () => ([]),
  },
  onDeleteLocalization: { 
    type: Function, default: null 
  },
});

const emit = defineEmits(['update:modelValue']);

// Composable 
// Importamos toda la lógica del composable.
// Le pasamos markets como computed ref para que sea reactivo.
const marketsRef = computed(() => props.markets);
const {
  activeMarket,
  activeLanguage,
  activeKey,
  currentMarket,
  currentLanguage,
  localizations,
  selectMarket,
  onTitleInput,
  marketHasContent,
} = useLocalizations(marketsRef);

// Sincronizamos el modelValue del padre con el estado interno.
// Cuando el padre pasa localizaciones existentes (edición), las cargamos.
// Usamos Object.assign para mantener la reactividad de Vue.
Object.assign(localizations.value, props.modelValue);

// Cada vez que el estado interno cambia, notificamos al padre (patrón v-model).
// Esto permite que el formulario padre siempre tenga los datos actualizados
// sin necesidad de acceder directamente al estado interno del componente.
import { watch } from 'vue';
watch(localizations, (newVal) => {
  emit('update:modelValue', newVal);
}, { deep: true, immediate: true });

//  Gestión del título con auto-slug
function handleTitleInput(value) {
  localizations.value[activeKey.value].title = value;
  onTitleInput(activeKey.value);
}

//  Helpers de errores
function hasError(field) {
  return !!props.errors[field];
}
function getError(field) {
  return props.errors[field] ?? '';
}
</script>