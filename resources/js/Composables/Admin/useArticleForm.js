import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { buildArticlePayload } from '@/Composables/Admin/useArticleLocalizations'
import { useArticleCrud } from '@/Composables/Admin/useArticleCrud'

export function useArticleForm({ api, onSuccess = null }) {

    const errors     = ref({})
    const processing = ref(false)

    const { createArticle, updateArticle, deleteLocalization } = useArticleCrud(api)

    // Crea un artículo nuevo.
    // Recibe form.value desde la página — no gestiona su propio form.
    async function submitCreate(formValue) {
        const payload = buildArticlePayload(formValue)

        if (payload.localizations.length === 0) {
            errors.value = { general: 'Debes rellenar al menos una localización con título.' }
            return false
        }

        return await _submit(() => createArticle(payload))
    }

    // Actualiza un artículo existente.
    // Recibe form.value desde la página.
    async function submitUpdate(articleId, formValue) {
        const payload = buildArticlePayload(formValue)
        return await _submit(() => updateArticle(articleId, payload))
    }

    // Elimina una localización concreta.
    // Devuelve { ok, message } para que la página decida qué hacer.
    async function removeLocalization(localizationId) {
        try {
            await deleteLocalization(localizationId)
            return { ok: true }
        } catch (e) {
            return {
                ok:      false,
                message: e.response?.data?.message || 'Error al eliminar la localización.',
            }
        }
    }

    // Lógica común de submit — privada al composable
    async function _submit(apiCall) {
        processing.value = true
        errors.value     = {}
        try {
            await apiCall()
            if (onSuccess) {
                onSuccess()
            } else {
                router.visit(route('admin.articles.index'))
            }
            return true
        } catch (e) {
            errors.value = e.response?.status === 422
                ? (e.response.data.errors || {})
                : { general: 'Error inesperado al guardar.' }
            return false
        } finally {
            processing.value = false
        }
    }

    return { errors, processing, submitCreate, submitUpdate, removeLocalization }
}