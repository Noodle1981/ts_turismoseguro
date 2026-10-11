import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from '../composables/useApi'
import type {
  UserResource,
  LoginPayload,
  RegisterRentPayload,
  RegisterClientPayload,
  ForgotPasswordPayload,
  ResetPasswordPayload,
  AuthResponse,
  UserRole,
} from '../types/auth'

export const useAuthStore = defineStore('auth', () => {
  const initialUser = (() => {
    try {
      const raw = localStorage.getItem('ts_auth_user')
      return raw ? JSON.parse(raw) : null
    } catch {
      return null
    }
  })()

  const user = ref<UserResource | null>(initialUser)
  const token = ref<string | null>(localStorage.getItem('ts_auth_token'))
  const isLoading = ref<boolean>(false)
  const errorMessage = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const userRole = computed<UserRole | null>(() => user.value?.role ?? null)

  // Sincronizar token en Axios
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  const setAuthData = (newToken: string, newUser: UserResource) => {
    token.value = newToken
    user.value = newUser
    localStorage.setItem('ts_auth_token', newToken)
    localStorage.setItem('ts_auth_user', JSON.stringify(newUser))
    api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`
  }

  const clearAuthData = () => {
    token.value = null
    user.value = null
    localStorage.removeItem('ts_auth_token')
    localStorage.removeItem('ts_auth_user')
    delete api.defaults.headers.common['Authorization']
  }

  const login = async (payload: LoginPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post<AuthResponse>('/auth/login', payload)
      setAuthData(response.data.token, response.data.user)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al iniciar sesión.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const registerRent = async (payload: RegisterRentPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post<AuthResponse>('/auth/register/rent', payload)
      setAuthData(response.data.token, response.data.user)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al registrarse.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const registerClient = async (payload: RegisterClientPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post<AuthResponse>('/auth/register/client', payload)
      setAuthData(response.data.token, response.data.user)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al registrar anfitrión.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchUser = async () => {
    if (!token.value) return null
    try {
      const response = await api.get<{ data: UserResource }>('/auth/me')
      user.value = response.data.data
      return user.value
    } catch {
      clearAuthData()
      return null
    }
  }

  const logout = async () => {
    try {
      if (token.value) {
        await api.post('/auth/logout')
      }
    } finally {
      clearAuthData()
    }
  }

  const forgotPassword = async (payload: ForgotPasswordPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post<{ message: string }>('/auth/forgot-password', payload)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al solicitar recuperación.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const resetPassword = async (payload: ResetPasswordPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post<{ message: string }>('/auth/reset-password', payload)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al restablecer contraseña.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    user,
    token,
    isLoading,
    errorMessage,
    isAuthenticated,
    userRole,
    login,
    registerRent,
    registerClient,
    fetchUser,
    logout,
    forgotPassword,
    resetPassword,
  }
})
