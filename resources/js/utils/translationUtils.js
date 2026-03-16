export function getTranslationValue(item, field, preferredLanguageIds = []) {
    const translations = Array.isArray(item?.translations) ? item.translations : [];
    if (translations.length === 0) {
        return null;
    }

    // Try preferred languages first (if IDs are provided)
    if (preferredLanguageIds.length > 0) {
        const preferred = preferredLanguageIds
            .map((languageId) => translations.find((translation) => Number(translation.language_id) === Number(languageId)))
            .find(Boolean);

        if (preferred && preferred[field]) {
            return preferred[field];
        }
    }

    // Default: return first non-empty value
    const firstWithField = translations.find((translation) => translation[field] && translation[field].toString().trim() !== '');
    return firstWithField?.[field] ?? null;
}

export function getTitleFromLocalizations(item) {
    const localizations = Array.isArray(item?.localizations) ? item.localizations : [];
    const firstTitle = localizations.find((loc) => loc.title && loc.title.toString().trim() !== '');
    return firstTitle?.title || 'Sin nombre';
}
