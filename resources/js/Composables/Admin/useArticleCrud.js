export function useArticleCrud(api) {
    const createArticle = (payload) => api.post('/api/v1/articles', payload);

    const updateArticle = (articleId, payload) =>
        api.put(`/api/v1/articles/${articleId}`, payload);

    const deleteLocalization = (localizationId) =>
        api.delete(`/api/v1/articles/localizations/${localizationId}`);

    return {
        createArticle,
        updateArticle,
        deleteLocalization,
    };
}
