/**
 * Format a date string to a readable format
 * @param {string} dateString - ISO date string
 * @returns {string} Formatted date (DD/MM/YYYY)
 */
export const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

/**
 * Format a date string with time
 * @param {string} dateString - ISO date string
 * @returns {string} Formatted date and time
 */
export const formatDateTime = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

/**
 * Get nested value from object using dot notation
 * @param {object} obj - Object to get value from
 * @param {string} path - Dot notation path (e.g., 'category.name')
 * @returns {any} Value or 'N/A' if not found
 */
export const getNestedValue = (obj, path) => {
    return path.split('.').reduce((acc, part) => acc?.[part], obj) || 'N/A';
};

/**
 * Format a multilingual field to get Spanish value first
 * @param {object|string} field - Multilingual field (e.g., {es: 'Hola', en: 'Hello'})
 * @returns {string} Spanish value or fallback
 */
export const getMultilingualValue = (field) => {
    if (!field) return 'N/A';
    if (typeof field === 'string') return field;
    return field.es || field.en || field.ca || Object.values(field)[0] || 'N/A';
};
