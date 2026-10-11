import { ref } from 'vue'
import type { Tenant } from '../../types/propietario'

export function usePropietarioTenants() {
  const tenantTab = ref<'proximos' | 'pasados'>('proximos')
  const showTenantModal = ref(false)
  const selectedTenant = ref<Tenant | null>(null)

  const openTenantDetail = (tenant: Tenant) => {
    selectedTenant.value = tenant
    showTenantModal.value = true
  }

  const setTenantRating = (tenant: Tenant, rating: 'up' | 'down') => {
    if (tenant.ownerRating === rating) {
      tenant.ownerRating = 'none'
    } else {
      tenant.ownerRating = rating
    }
  }

  return {
    tenantTab,
    showTenantModal,
    selectedTenant,
    openTenantDetail,
    setTenantRating,
  }
}
