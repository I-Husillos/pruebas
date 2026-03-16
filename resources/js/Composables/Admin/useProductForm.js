import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { buildProductPayload } from '@/Composables/Admin/useProductLocalizations'
import { useProductCrud } from '@/Composables/Admin/useProductCrud'

export function useProductForm({ api, onSuccess = null }) {

    const errors     = ref({})
    const processing = ref(false)

    const { createProduct, updateProduct, deleteLocalization } = useProductCrud(api)

    async function submitCreate(formValue) {
        const payload = buildProductPayload(formValue)

        if (payload.localizations.length === 0) {
            errors.value = { general: 'Debes rellenar al menos una localización con título.' }
            return false
        }

        return await _submit(() => createProduct(payload))
    }

    async function submitUpdate(productId, formValue) {
        const payload = buildProductPayload(formValue)
        return await _submit(() => updateProduct(productId, payload))
    }

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

    async function _submit(apiCall) {
        processing.value = true
        errors.value     = {}
        try {
            await apiCall()
            if (onSuccess) {
                onSuccess()
            } else {
                router.visit(route('admin.products.index'))
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
