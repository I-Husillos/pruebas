export function useProductCrud(api) {
    const createProduct = (payload) => api.post('/api/v1/products', payload);

    const updateProduct = (productId, payload) =>
        api.put(`/api/v1/products/${productId}`, payload);

    const deleteLocalization = (localizationId) =>
        api.delete(`/api/v1/products/localizations/${localizationId}`);

    return {
        createProduct,
        updateProduct,
        deleteLocalization,
    };
}
