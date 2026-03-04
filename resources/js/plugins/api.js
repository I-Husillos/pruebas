import ApiClient from '@/api/client';

export default {
    install(app) {
        app.config.globalProperties.$createApiClient = (token) => {
            return new ApiClient(token);
        };
    }
};
