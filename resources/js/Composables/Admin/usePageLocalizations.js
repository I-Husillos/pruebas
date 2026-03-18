/**
 * Build the initial localizations object (keyed by "{market_code}_{lang_code}")
 * from the page.localizations array returned by the API/backend.
 *
 * Used in Edit.vue to populate the form from an existing page.
 */
export function buildInitialPageLocalizations(localizations = [], markets = []) {
    const result = {}

    for (const loc of localizations) {
        const market = markets.find((m) => m.id === loc.market_id)
        const language = market?.languages.find((l) => l.id === loc.language_id)

        if (market && language) {
            result[`${market.code}_${language.code}`] = { ...loc }
        }
    }

    return result
}

/**
 * Filter localizations that have at least a slug (minimum required field for a page).
 */
export function getFilledPageLocalizations(localizations = {}) {
    return Object.values(localizations).filter(
        (loc) => (loc?.title || '').trim() !== ''
    )
}

/**
 * Build the full payload sent to the API (POST/PUT /api/v1/pages).
 */
export function buildPagePayload(form) {
    return {
        status:        form.status,
        localizations: getFilledPageLocalizations(form.localizations),
    }
}