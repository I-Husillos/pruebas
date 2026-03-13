import axios from 'axios';

class ApiClient {
    constructor(token) {
        this.token = token;
        this.client = axios.create({
            baseURL: window.location.origin,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        this.setupInterceptors();
    }

    setupInterceptors() {
        // Request interceptor
        this.client.interceptors.request.use((config) => {
            if (this.token) {
                config.headers.Authorization = `Bearer ${this.token}`;
            }
            return config;
        });

        // Response interceptor
        this.client.interceptors.response.use(
            response => response,
            async (error) => {
                const originalRequest = error.config;

                if (error.response?.status === 401 && !originalRequest._retry) {
                    originalRequest._retry = true;

                    try {
                        const response = await axios.post('/oauth/token/refresh', {
                            refresh_token: localStorage.getItem('refresh_token')
                        });

                        this.token = response.data.access_token;
                        localStorage.setItem('passport_token', this.token);
                        localStorage.setItem('refresh_token', response.data.refresh_token);

                        originalRequest.headers.Authorization = `Bearer ${this.token}`;
                        return this.client(originalRequest);
                    } catch (refreshError) {
                        window.location.href = '/login';
                        return Promise.reject(refreshError);
                    }
                }

                return Promise.reject(error);
            }
        );
    }

    async get(url, params = {}) {
        return this.client.get(url, { params });
    }

    async post(url, data = {}) {
        return this.client.post(url, data);
    }

    async put(url, data = {}) {
        return this.client.put(url, data);
    }

    async patch(url, data = {}) {
        return this.client.patch(url, data);
    }

    async delete(url) {
        return this.client.delete(url);
    }

    async upload(url, formData, onProgress = null) {
        return this.client.post(url, formData, {
            headers: {
                // Al poner undefined, axios elimina el Content-Type por defecto
                // y el navegador lo establece automáticamente como:
                // multipart/form-data; boundary=----WebKitFormBoundary...
                // Sin el boundary correcto, el servidor no puede leer el archivo.
                'Content-Type': undefined,
            },
            onUploadProgress: onProgress ? (e) => {
                onProgress(Math.round((e.loaded * 100) / e.total));
            } : undefined,
        });
    }
}

export default ApiClient;
