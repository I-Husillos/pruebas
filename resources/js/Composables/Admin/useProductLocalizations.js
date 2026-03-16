export function buildInitialProductLocalizations(localizations = [], markets = []) {
    const result = {};

    for (const localization of localizations) {
        const market = markets.find((item) => item.id === localization.market_id);
        const language = market?.languages.find((item) => item.id === localization.language_id);

        if (market && language) {
            result[`${market.code}_${language.code}`] = { ...localization };
        }
    }

    return result;
}

export function getFilledProductLocalizations(localizations = {}) {
    return Object.values(localizations).filter(
        (localization) => (localization?.title || '').trim() !== ''
    );
}

export function buildProductPayload(form) {
    return {
        product_category_id: form.product_category_id,
        code:                form.code,
        status:              form.status,
        images:              form.images,
        related_treatments:  form.related_treatments,
        order:               form.order,
        localizations:       getFilledProductLocalizations(form.localizations),
    };
}
