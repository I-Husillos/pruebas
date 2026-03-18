import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useUserForm({ api, onSuccess = null }) {
    const errors = ref({})
    const processing = ref(false)

    async function submitCreate(formValue) {
        return await _submit(() => api.post('/api/v1/users', formValue))
    }

    async function submitUpdate(userId, formValue) {
        return await _submit(() => api.put(`/api/v1/users/${userId}`, formValue))
    }

    async function _submit(apiCall) {
        processing.value = true
        errors.value = {}

        try {
            await apiCall()

            if (onSuccess) {
                onSuccess()
            } else {
                router.visit(route('admin.users.index'))
            }

            return true
        } catch (e) {
            errors.value = e.response?.status === 422
                ? (e.response?.data?.errors ?? {})
                : { general: 'Error inesperado al guardar.' }

            return false
        } finally {
            processing.value = false
        }
    }

    return { errors, processing, submitCreate, submitUpdate }
}
