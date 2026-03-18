import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { buildTreatmentPayload } from '@/Composables/Admin/useTreatmentLocalizations'
import { useTreatmentCrud } from '@/Composables/Admin/useTreatmentCrud'

export function useTreatmentForm({ api, onSuccess = null }) {

    const errors     = ref({})
    const processing = ref(false)

    const { createTreatment, updateTreatment, deleteLocalization } = useTreatmentCrud(api)

    async function submitCreate(formValue) {
        const payload = buildTreatmentPayload(formValue)

        if (payload.localizations.length === 0) {
            errors.value = { general: 'Debes rellenar los campos Título, Meta Title y Meta Description' }
            return false
        }

        return await _submit(() => createTreatment(payload))
    }

    async function submitUpdate(treatmentId, formValue) {
        const payload = buildTreatmentPayload(formValue)
        return await _submit(() => updateTreatment(treatmentId, payload))
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
                router.visit(route('admin.treatments.index'))
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
