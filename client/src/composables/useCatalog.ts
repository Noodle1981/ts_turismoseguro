import { ref } from 'vue'
import { useApi } from './useApi'
import type { Property, Department, Experience, PropertyFilters, ExperienceFilters, AvailabilityItem } from '../types/catalog'

export function useCatalog() {
  const { api } = useApi()
  const isLoading = ref(false)
  const errorMessage = ref<string | null>(null)

  const getDepartments = async (): Promise<Department[]> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get('/departments')
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar departamentos.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getProperties = async (filters: PropertyFilters = {}) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get('/properties', { params: filters })
      return {
        data: response.data.data as Property[],
        meta: response.data.meta,
      }
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar propiedades.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getPropertyBySlug = async (slug: string): Promise<Property> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get(`/properties/${slug}`)
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar la propiedad.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getPropertyAvailability = async (id: number, startDate?: string, endDate?: string): Promise<AvailabilityItem[]> => {
    try {
      const response = await api.get(`/properties/${id}/availability`, {
        params: { start_date: startDate, end_date: endDate },
      })
      return response.data.data
    } catch (err: any) {
      return []
    }
  }

  const getExperiences = async (filters: ExperienceFilters = {}) => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get('/experiences', { params: filters })
      return response.data.data as Experience[]
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar experiencias.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getExperienceBySlug = async (slug: string): Promise<Experience> => {
    isLoading.value = true
    errorMessage.value = null
    try {
      const response = await api.get(`/experiences/${slug}`)
      return response.data.data
    } catch (err: any) {
      errorMessage.value = err.response?.data?.message || 'Error al cargar la experiencia.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    errorMessage,
    getDepartments,
    getProperties,
    getPropertyBySlug,
    getPropertyAvailability,
    getExperiences,
    getExperienceBySlug,
  }
}
