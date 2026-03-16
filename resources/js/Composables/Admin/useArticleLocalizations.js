export function buildInitialArticleLocalizations(localizations = [], markets = []) {
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

export function getFilledArticleLocalizations(localizations = {}) {
    return Object.values(localizations).filter(
        (localization) => (localization?.title || '').trim() !== ''
    );
}

export function buildArticlePayload(form) {
    return {
        article_category_id: form.article_category_id,
        status: form.status,
        images: form.images,
        localizations: getFilledArticleLocalizations(form.localizations),
    };
}
