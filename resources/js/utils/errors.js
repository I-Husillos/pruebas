export function normalizeErrorMessage(error) {
    if (Array.isArray(error)) {
        return error[0] ?? ''
    }

    return typeof error === 'string' ? error : ''
}

export function getErrorMessage(errors = {}, field) {
    return normalizeErrorMessage(errors?.[field])
}

export function hasError(errors = {}, field) {
    return getErrorMessage(errors, field) !== ''
}
