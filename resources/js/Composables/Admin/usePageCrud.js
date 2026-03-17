export function usePageCrud(api) {
    const createPage = (payload) =>
        api.post('/api/v1/pages', payload)

    const updatePage = (pageId, payload) =>
        api.put(`/api/v1/pages/${pageId}`, payload)

    const deletePage = (pageId) =>
        api.delete(`/api/v1/pages/${pageId}`)

    const deleteLocalization = (localizationId) =>
        api.delete(`/api/v1/pages/localizations/${localizationId}`)

    return { createPage, updatePage, deletePage, deleteLocalization }
}