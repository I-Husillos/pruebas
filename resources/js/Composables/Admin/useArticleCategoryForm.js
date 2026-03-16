import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useArticleCategoryForm({ api, category = null, languages = [], onSuccess = null }) {

    // Construye el objeto inicial de traducciones para edición.
    // En creación devuelve {} — TranslationTabs lo inicializa desde cero.
    function buildInitialTranslations() {
        if (!category || !languages.length) return {}

        const result = {}
        for (const lang of languages) {
            const existing = category.translations?.find(
                t => Number(t.language_id) === lang.id
            )
            result[lang.code] = {
                language_id:  lang.id,
                title:        existing?.title       ?? '',
                slug:         existing?.slug        ?? '',
                description:  existing?.description ?? '',
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
                seo_metadata: null,
            }))
    }

    function buildPayload() {
        return {
            status:       form.value.active ? 'active' : 'inactive',
            order:        Number(form.value.order ?? 0),
            translations: getValidTranslations(),
        }
    }

    async function submitCreate() {
        if (getValidTranslations().length === 0) {
            errors.value = { general: 'Debes rellenar al menos un idioma.' }
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

    return { form, errors, processing, submitCreate, submitUpdate }
}