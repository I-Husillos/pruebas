export function buildInitialTreatmentLocalizations(localizations = [], markets = []) {
    const result = {};

    for (const localization of localizations) {
        const market   = markets.find((item) => item.id === localization.market_id);
        const language = market?.languages.find((item) => item.id === localization.language_id);

        if (market && language) {
            result[`${market.code}_${language.code}`] = { ...localization };
        }
    }

    return result;
}

export function getFilledTreatmentLocalizations(localizations = {}) {
    return Object.values(localizations).filter(
        (localization) => (localization?.title || '').trim() !== ''
    );
}

export function buildTreatmentPayload(form) {
    return {
        treatment_category_id: form.treatment_category_id,
        status:                form.status,
        images:                form.images,
        related_products:      form.related_products,
        order:                 form.order,
        localizations:         getFilledTreatmentLocalizations(form.localizations),
    };
}
