import { ref, computed } from 'vue'
import { useAuth } from './useAuth'
import type { UserRole } from '../types/auth'

// Estado global reactivo para permitir al personal conmutar entre vista técnica y vista cliente/turista sin desloguearse
const isViewingAsUser = ref<boolean>(false)

export function usePermissions() {
  const { user } = useAuth()

  const currentRole = computed<UserRole | null>(() => user.value?.role ?? null)

  const isSuperAdmin = computed(() => currentRole.value === 'super_admin')
  const isAdmin = computed(() => currentRole.value === 'admin' || currentRole.value === 'super_admin')
  const isSupervisor = computed(() => currentRole.value === 'supervisor')
  const isValidator = computed(() => currentRole.value === 'validator')
  const isClient = computed(() => currentRole.value === 'client')
  const isRent = computed(() => currentRole.value === 'rent')

  // Personal interno (Validador, Supervisor, Admin, Super Admin)
  const isStaff = computed(() => {
    return ['validator', 'supervisor', 'admin', 'super_admin'].includes(currentRole.value ?? '')
  })

  const toggleViewAsUser = () => {
    isViewingAsUser.value = !isViewingAsUser.value
  }

  const setViewAsUser = (value: boolean) => {
    isViewingAsUser.value = value
  }

  const hasRole = (roles: UserRole | UserRole[]): boolean => {
    if (!currentRole.value) return false
    if (Array.isArray(roles)) {
      return roles.includes(currentRole.value)
    }
    return currentRole.value === roles
  }

  return {
    currentRole,
    isSuperAdmin,
    isAdmin,
    isSupervisor,
    isValidator,
    isClient,
    isRent,
    isStaff,
    isViewingAsUser,
    toggleViewAsUser,
    setViewAsUser,
    hasRole,
  }
}
