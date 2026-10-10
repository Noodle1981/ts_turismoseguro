import { computed } from 'vue'
import { useAuth } from './useAuth'
import type { UserRole } from '../types/auth'

export function usePermissions() {
  const { user } = useAuth()

  const currentRole = computed<UserRole | null>(() => user.value?.role ?? null)

  const isSuperAdmin = computed(() => currentRole.value === 'super_admin')
  const isSupervisor = computed(() => currentRole.value === 'supervisor')
  const isValidator = computed(() => currentRole.value === 'validator')
  const isClient = computed(() => currentRole.value === 'client')
  const isRent = computed(() => currentRole.value === 'rent')

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
    isSupervisor,
    isValidator,
    isClient,
    isRent,
    hasRole,
  }
}
