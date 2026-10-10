import { storeToRefs } from 'pinia'
import { useAuthStore } from '../stores/auth'

export function useAuth() {
  const store = useAuthStore()
  const { user, token, isAuthenticated, userRole, isLoading, errorMessage } = storeToRefs(store)

  return {
    user,
    token,
    isAuthenticated,
    userRole,
    isLoading,
    errorMessage,
    login: store.login,
    registerRent: store.registerRent,
    registerClient: store.registerClient,
    fetchUser: store.fetchUser,
    logout: store.logout,
    forgotPassword: store.forgotPassword,
    resetPassword: store.resetPassword,
  }
}
