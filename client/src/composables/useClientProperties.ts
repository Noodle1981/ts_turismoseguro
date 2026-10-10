import { ref } from 'vue'
import { useApi } from './useApi'
import type { Property } from '../types/catalog'

export interface CreatePropertyPayload {
  title: string
  department_id: number
  property_type: string
  location: string
  address?: string
  description: string
  price: number
  price_type: string
  capacity: number
  bedrooms?: number
  bathrooms?: number
  host_whatsapp?: string
  host_phone?: string
  amenities?: string[]
  rules?: string[]
}

export interface UpdateAvailabilityPayload {
  start_date: string
  end_date: string
  action: 'block' | 'free'
}

export function useClientProperties() {
  const { api } = useApi()
  const isLoading = ref(false)
  const errorMessage = ref<string | null>(null)

  const getMyProperties = async (): Promise<Property[]> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get('/client/properties')
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al obtener tus alojamientos.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getMyPropertyById = async (id: number): Promise<Property> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get(`/client/properties/${id}`)
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar los datos del alojamiento.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const createProperty = async (payload: CreatePropertyPayload): Promise<Property> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post('/client/properties', payload)
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al crear la propiedad.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const updateProperty = async (id: number, payload: Partial<CreatePropertyPayload>): Promise<Property> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.put(`/client/properties/${id}`, payload)
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al actualizar la propiedad.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getPropertyAvailability = async (id: number) => {
    try {
      const response = await api.get(`/client/properties/${id}/availability`)
      return response.data.data
    } catch (err) {
      return []
    }
  }

  const updatePropertyAvailability = async (id: number, payload: UpdateAvailabilityPayload) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post(`/client/properties/${id}/availability`, payload)
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al actualizar fechas en el calendario.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const requestVerification = async (propertyId: number, notes?: string) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.post('/client/verifications/request', {
        property_id: propertyId,
        notes,
      })
      return response.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al solicitar la inspección técnica.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    errorMessage,
    getMyProperties,
    getMyPropertyById,
    createProperty,
    updateProperty,
    getPropertyAvailability,
    updatePropertyAvailability,
    requestVerification,
  }
}
