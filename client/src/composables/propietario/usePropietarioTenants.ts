import { ref, type Ref } from 'vue'
import { api } from '../useApi'
import { useAuth } from '../useAuth'
import type { Tenant, PropietarioOferta } from '../../types/propietario'

export function usePropietarioTenants(selectedOffer?: Ref<PropietarioOferta | undefined>) {
  const { isAuthenticated } = useAuth()
  const tenantTab = ref<'proximos' | 'pasados'>('proximos')
  const showTenantModal = ref(false)
  const selectedTenant = ref<Tenant | null>(null)
  const isLoadingTenants = ref(false)

  /**
   * Carga los inquilinos reales de la propiedad desde la base de datos de Laravel
   */
  const fetchTenants = async (propertyId?: number) => {
    const id = propertyId ?? selectedOffer?.value?.id
    if (!isAuthenticated.value || !id) return

    isLoadingTenants.value = true
    try {
      const response = await api.get(`/client/properties/${id}/tenants`)
      if (response.data && selectedOffer?.value) {
        if (Array.isArray(response.data.proximos)) {
          selectedOffer.value.tenants = response.data.proximos
        }
        if (Array.isArray(response.data.pasados)) {
          selectedOffer.value.pastTenants = response.data.pasados
        }
      }
    } catch (err: any) {
      console.warn('Inquilinos: utilizando registros en memoria.', err)
    } finally {
      isLoadingTenants.value = false
    }
  }

  const openTenantDetail = (tenant: Tenant) => {
    selectedTenant.value = tenant
    showTenantModal.value = true
  }

  /**
   * Califica al inquilino y persiste la valoración en la BD
   */
  const setTenantRating = async (tenant: Tenant, rating: 'up' | 'down') => {
    const newRating = tenant.ownerRating === rating ? 'none' : rating
    tenant.ownerRating = newRating

    if (isAuthenticated.value && tenant.id) {
      try {
        await api.post(`/client/bookings/${tenant.id}/rate`, {
          rating: newRating,
        })
      } catch (err: any) {
        console.warn('No se pudo persistir la calificación del inquilino en la base de datos.', err)
      }
    }
  }

  return {
    tenantTab,
    showTenantModal,
    selectedTenant,
    isLoadingTenants,
    fetchTenants,
    openTenantDetail,
    setTenantRating,
  }
}
