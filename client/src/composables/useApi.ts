import axios, { type AxiosInstance, type AxiosError } from 'axios'

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/v1'

export const api: AxiosInstance = axios.create({
  baseURL: apiBaseUrl,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

api.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    if (error.response) {
      const { status } = error.response
      if (status === 401) {
        // Redirección o limpieza de sesión cuando aplique
      } else if (status === 403) {
        console.warn('Acceso denegado (403): Permisos insuficientes.')
      } else if (status === 422) {
        // Error de validación de Form Request
      } else if (status >= 500) {
        console.error('Error del servidor (500):', error.response.data)
      }
    }
    return Promise.reject(error)
  }
)

export function useApi() {
  return {
    api,
  }
}
