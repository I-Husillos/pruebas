<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900">Contenido Localizado</h3>
      <p class="mt-1 text-sm text-gray-500">
        Selecciona un mercado y luego el idioma.
      </p>
    </div>

    <!-- Selector compacto de mercado/idioma -->
    <div v-if="currentMarket">
      <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mercado</label>
          <select
            :value="activeMarket"
            @change="selectMarket($event.target.value)"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option v-for="market in markets" :key="market.code" :value="market.code">
              {{ marketHasContent(market.code) ? '✓ ' : '' }}{{ market.name }} · {{ market.region }}
            </option>
          </select>
          <p class="mt-1 text-xs text-gray-400">
            {{ completedMarketsCount }} de {{ markets.length }} mercados con contenido
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Idioma</label>
          <select
            v-model="activeLanguage"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option v-for="lang in currentMarket.languages" :key="lang.code" :value="lang.code">
              {{ hasContent(activeMarket, lang.code) ? '✓ ' : '' }}{{ lang.name }}{{ lang.is_default ? ' · default' : '' }}
            </option>
          </select>
          <p class="mt-1 text-xs text-gray-400">
            {{ completedLanguagesCount }} de {{ currentMarket.languages.length }} idiomas con contenido en {{ currentMarket.name }}
          </p>
        </div>
      </div>

      <!--  ÁREA DE CONTENIDO: campos para la combinación activa  -->
      <div v-if="activeKey && localizations[activeKey]">

        <div
          v-if="activeLocalizationId"
          class="mb-4 flex justify-end"
        >
          <button
            v-if="onDeleteLocalization"
            type="button"
            @click="onDeleteLocalization(activeLocalizationId)"
            class="inline-flex items-center rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100"
          >
            Eliminar localización actual
          </button>
        </div>

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
                Meta Title <span class="text-red-500">*</span>
                <span class="font-normal text-gray-400 text-xs ml-1">
                  (aparece en Google y en la pestaña del navegador)
                </span>
              </label>
              <input :value="localizations[activeKey].seo_metadata?.title" @input="localizations[activeKey].seo_metadata = {
                ...localizations[activeKey].seo_metadata,
                title: $event.target.value
              }" type="text" maxlength="60"
                :class="hasError(`localizations.${activeKey}.seo_metadata.title`)
                  ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                  : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                :placeholder="`Título SEO en ${currentLanguage?.name} (máx. 60 caracteres)`" />
              <p class="mt-1 text-xs text-gray-400">
                {{ (localizations[activeKey].seo_metadata?.title || '').length }}/60 caracteres
              </p>
              <p v-if="hasError(`localizations.${activeKey}.seo_metadata.title`)" class="mt-1 text-sm text-red-600">
                {{ getError(`localizations.${activeKey}.seo_metadata.title`) }}
              </p>
            </div>

            <!-- SEO Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Meta Description <span class="text-red-500">*</span>
                <span class="font-normal text-gray-400 text-xs ml-1">
                  (resumen que muestra Google en los resultados de búsqueda)
                </span>
              </label>
              <textarea :value="localizations[activeKey].seo_metadata?.description" @input="localizations[activeKey].seo_metadata = {
                ...localizations[activeKey].seo_metadata,
                description: $event.target.value
              }" rows="2" maxlength="160"
                :class="hasError(`localizations.${activeKey}.seo_metadata.description`)
                  ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
                  : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'"
                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
                :placeholder="`Descripción SEO en ${currentLanguage?.name} (máx. 1000 caracteres)`" />
              <p class="mt-1 text-xs text-gray-400">
                {{ (localizations[activeKey].seo_metadata?.description || '').length }}/1000 caracteres
              </p>
              <p v-if="hasError(`localizations.${activeKey}.seo_metadata.description`)" class="mt-1 text-sm text-red-600">
                {{ getError(`localizations.${activeKey}.seo_metadata.description`) }}
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
            <BlockEditor :key="activeKey" v-model="localizations[activeKey].content" :forms="forms" :market="activeMarket"/>
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
import { computed, watch } from 'vue';
import { useLocalizations } from '@/Composables/useLocalizations';
import { buildLocalizationKey } from '@/utils/localizations';
import BlockEditor from '@/Components/Admin/BlockEditor.vue';
import { normalizeErrorMessage } from '@/utils/errors';


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
  hasContent,
  marketHasContent,
} = useLocalizations(marketsRef);

const completedMarketsCount = computed(() =>
  props.markets.filter((market) => marketHasContent(market.code)).length
);

const completedLanguagesCount = computed(() =>
  (currentMarket.value?.languages ?? []).filter((lang) => hasContent(activeMarket.value, lang.code)).length
);

const activeLocalizationId = computed(() =>
  activeKey.value ? localizations.value[activeKey.value]?.id ?? null : null
);

// Sincronizamos el modelValue del padre con el estado interno.
// Cuando el padre pasa localizaciones existentes (edición), las cargamos.
// Usamos Object.assign para mantener la reactividad de Vue.
Object.assign(localizations.value, props.modelValue);

// Cada vez que el estado interno cambia, notificamos al padre (patrón v-model).
// Esto permite que el formulario padre siempre tenga los datos actualizados
// sin necesidad de acceder directamente al estado interno del componente.
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
  return !!getError(field);
}
function getError(field) {
  const directError = props.errors[field];

  if (directError) {
    return normalizeErrorMessage(directError);
  }

  const active = activeKey.value;
  const prefix = `localizations.${active}.`;

  if (!active || !field.startsWith(prefix)) {
    return '';
  }

  const activeLocalization = localizations.value[active];

  if (!activeLocalization) {
    return '';
  }

  const subField = field.slice(prefix.length);
  const submittedLocalizations = Object.values(localizations.value).filter(
    (localization) => (localization?.title || '').trim() !== ''
  );

  const index = submittedLocalizations.findIndex((localization) => (
    String(localization?.market_id) === String(activeLocalization?.market_id)
    && String(localization?.language_id) === String(activeLocalization?.language_id)
  ));

  if (index === -1) {
    return '';
  }

  const wildcardError = props.errors[`localizations.${index}.${subField}`];
  return normalizeErrorMessage(wildcardError);
}
</script>