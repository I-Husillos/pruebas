export function useTreatmentCrud(api) {
    const createTreatment = (payload) => api.post('/api/v1/treatments', payload);

    const updateTreatment = (treatmentId, payload) =>
        api.put(`/api/v1/treatments/${treatmentId}`, payload);

    const deleteLocalization = (localizationId) =>
        api.delete(`/api/v1/treatments/localizations/${localizationId}`);

    return {
        createTreatment,
        updateTreatment,
        deleteLocalization,
    };
}
