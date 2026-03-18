import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useArticleCategoryForm({ api, category = null, languages = [], onSuccess = null, translations = [] }) {

    // Construye el objeto inicial de traducciones para edición.
    // En creación devuelve {} — TranslationTabs lo inicializa desde cero.
    function buildInitialTranslations() {
        if (!languages.length) return {}

        const result = {}
        for (const lang of languages) {
            const existing = category?.translations?.find(
                t => Number(t.language_id) === lang.id
            ) || translations.find(
                t => Number(t.language_id) === lang.id
            )
            result[lang.code] = {
                language_id:  lang.id,
                title:        existing?.title       ?? '',
                slug:         existing?.slug        ?? '',
                description:  existing?.description ?? '',
                seo_metadata: existing?.seo_metadata ?? { title: '', description: '' },
            }
        }
        return result
    }

    const form = ref({
        active:       category ? category.status === 'active' : true,
        order:        category?.order ?? 0,
        translations: buildInitialTranslations(),
    })

    const errors     = ref({})
    const processing = ref(false)

    function getValidTranslations() {
        return Object.values(form.value.translations)
            .filter(t => t.title?.trim() && t.slug?.trim())
            .map(t => ({
                language_id:  t.language_id,
                title:        t.title.trim(),
                slug:         t.slug.trim(),
                description:  t.description?.trim() || null,
                seo_metadata: {
                    title: t.seo_metadata?.title?.trim() || null,
                    description: t.seo_metadata?.description?.trim() || null,
                },
            }))
    }

    function getPayloadTranslations() {
        return Object.values(form.value.translations)
        .filter(t => t.title?.trim())   // <-- solo los que tienen título
        .map(t => ({
            language_id:  t.language_id,
            title:        t.title.trim(),
            slug:         t.slug.trim(),
            description:  t.description?.trim() || '',
            seo_metadata: {
                title:       t.seo_metadata?.title?.trim() || '',
                description: t.seo_metadata?.description?.trim() || '',
            },
        }))
    }

    function buildPayload() {
        return {
            status:       form.value.active ? 'active' : 'inactive',
            order:        Number(form.value.order ?? 0),
            translations: getPayloadTranslations(),
        }
    }

    async function submitCreate() {
        if (getValidTranslations().length === 0) {
            errors.value = buildMissingTranslationErrors()
            return false
        }
        return await _submit(() => api.post('/api/v1/article-categories', buildPayload()))
    }

    async function submitUpdate(id) {
        return await _submit(() => api.put(`/api/v1/article-categories/${id}`, buildPayload()))
    }

    async function _submit(apiCall) {
        processing.value = true
        errors.value     = {}
        try {
            await apiCall()
            if (onSuccess) {
                onSuccess()
            } else {
                router.visit(route('admin.article-categories.index'))
            }
            return true
        } catch (e) {
            errors.value = e.response?.status === 422
                ? (e.response.data.errors ?? {})
                : { general: 'Error inesperado al guardar.' }
            return false
        } finally {
            processing.value = false
        }
    }

    function buildMissingTranslationErrors() {
        const translations = Object.keys(form.value.translations || {})

        if (translations.length === 0) {
            return { general: 'Debes rellenar los campos obligatorios.' }
        }

        const message = 'Este campo es obligatorio.'
        const fieldErrors = {}

        for (const key of translations) {
            fieldErrors[`translations.${key}.title`] = message
            fieldErrors[`translations.${key}.slug`] = message
            fieldErrors[`translations.${key}.description`] = message
            fieldErrors[`translations.${key}.seo_metadata.title`] = message
            fieldErrors[`translations.${key}.seo_metadata.description`] = message
        }

        return fieldErrors
    }

    // Dentro de useArticleCategoryForm — añade esta función y exponla en el return
    function getSubmittedTranslationsOrder() {
        // Devuelve los language_id en el mismo orden que getPayloadTranslations()
        // Esto permite que TranslationTabs calcule el índice correcto
        // para mapear "translations.0.title" al idioma correcto
        return Object.values(form.value.translations)
            .filter(t => t.title?.trim())
            .map(t => t.language_id)
    }

    return { form, errors, processing, submitCreate, submitUpdate, getSubmittedTranslationsOrder }
}