import { ref, computed } from 'vue'
import { api } from '../useApi'
import { useAuth } from '../useAuth'

export interface DocketItem {
  id: string
  code: string
  property_id: number
  propertyTitle: string
  property_type: string
  department: string
  department_id?: number
  address: string
  ownerName: string
  ownerDni: string
  ownerPhone: string
  hoursAgo: string
  status: 'pending' | 'assigned' | 'visited' | 'under_supervision' | 'approved' | 'rejected'
  priority: 'urgent' | 'normal'
  photosCount: number
  imageUrl: string
  amenities: string[]
  notes?: string
  rejection_reason?: string
  security_token?: string
  validator_id?: number | null
  validator_name?: string | null
  supervisor_id?: number | null
  payout_account_type?: 'cbu' | 'cvu' | 'billetera_virtual' | null
  payout_account_number?: string | null
  payout_bank_name?: string | null
  payout_verified?: boolean
  visit_date?: string | null
  checklist?: {
    cerco_pileta?: boolean
    disyuntor?: boolean
    matafuegos?: boolean
    cbu_verificado?: boolean
    [key: string]: any
  } | null
}

export interface DocketMetrics {
  pending: number
  inProgress: number
  approved: number
  rejected: number
}

export function useSupervisorDictamenes() {
  const { user } = useAuth()

  const dockets = ref<DocketItem[]>([])
  const metrics = ref<DocketMetrics>({
    pending: 0,
    inProgress: 0,
    approved: 0,
    rejected: 0,
  })

  const isLoading = ref<boolean>(false)
  const isResolving = ref<boolean>(false)
  const actionFeedback = ref<{ type: 'approve' | 'modify' | 'reject'; message: string } | null>(null)

  const activeTab = ref<'todos' | 'pending' | 'modifications' | 'approved' | 'rejected'>('pending')
  const searchQuery = ref<string>('')
  const selectedValidatorFilter = ref<string>('all')
  const selectedDepartmentFilter = ref<string>('all')
  const selectedDocketId = ref<string>('')

  const availableValidators = ref<Array<{ id: number; name: string }>>([])

  const fetchDockets = async () => {
    isLoading.value = true
    try {
      const res = await api.get('/staff/verifications')
      const items: DocketItem[] = res.data?.data || []
      dockets.value = items

      if (res.data?.metrics) {
        metrics.value = {
          pending: res.data.metrics.pending || 0,
          inProgress: res.data.metrics.inProgress || 0,
          approved: res.data.metrics.approved || 0,
          rejected: res.data.metrics.rejected || 0,
        }
      }

      if (Array.isArray(res.data?.available_validators)) {
        availableValidators.value = res.data.available_validators
      }

      // Si no hay seleccionado o el seleccionado ya no existe, elegir el primero si hay
      if (!selectedDocketId.value && items.length > 0) {
        selectedDocketId.value = items[0].id
      }
    } catch (err: any) {
      console.error('Error al obtener expedientes de supervisión desde la DB:', err)
    } finally {
      isLoading.value = false
    }
  }

  // Filtrado de expedientes reactivo basado estrictamente en DB
  const filteredDockets = computed(() => {
    return dockets.value.filter((docket) => {
      // Filtro por tab
      if (activeTab.value === 'pending') {
        if (!['pending', 'assigned', 'visited', 'under_supervision'].includes(docket.status)) {
          return false
        }
      } else if (activeTab.value === 'modifications') {
        if (docket.status !== 'under_supervision' && docket.status !== 'visited') {
          return false
        }
      } else if (activeTab.value === 'approved') {
        if (docket.status !== 'approved') {
          return false
        }
      } else if (activeTab.value === 'rejected') {
        if (docket.status !== 'rejected') {
          return false
        }
      }

      // Filtro por buscador
      if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase().trim()
        const matchesCode = docket.code?.toLowerCase().includes(q)
        const matchesTitle = docket.propertyTitle?.toLowerCase().includes(q)
        const matchesOwner = docket.ownerName?.toLowerCase().includes(q)
        const matchesDni = docket.ownerDni?.toLowerCase().includes(q)
        const matchesDept = docket.department?.toLowerCase().includes(q)
        if (!matchesCode && !matchesTitle && !matchesOwner && !matchesDni && !matchesDept) {
          return false
        }
      }

      // Filtro por validador
      if (selectedValidatorFilter.value !== 'all') {
        if (String(docket.validator_id) !== selectedValidatorFilter.value) {
          return false
        }
      }

      // Filtro por departamento
      if (selectedDepartmentFilter.value !== 'all') {
        if (docket.department.toLowerCase() !== selectedDepartmentFilter.value.toLowerCase()) {
          return false
        }
      }

      return true
    })
  })

  // Expediente seleccionado en pantalla
  const selectedDocket = computed(() => {
    return (
      dockets.value.find((d) => d.id === selectedDocketId.value) ||
      filteredDockets.value[0] ||
      null
    )
  })

  // Ejecución de dictamen sobre DB
  const resolveDocket = async (action: 'approve' | 'modify' | 'reject', notes: string) => {
    if (!selectedDocket.value) return false
    isResolving.value = true
    actionFeedback.value = null

    try {
      const res = await api.put(`/staff/verifications/${selectedDocket.value.id}/resolve`, {
        action,
        notes,
      })

      actionFeedback.value = {
        type: action,
        message: res.data?.message || 'Dictamen procesado correctamente.',
      }

      // Actualizar listado y métricas en vivo desde la DB
      await fetchDockets()
      return true
    } catch (err: any) {
      console.error('Error al emitir dictamen sobre la base de datos:', err)
      return false
    } finally {
      isResolving.value = false
    }
  }

  // Asignar inspector a una solicitud pendiente desde la Mesa de Supervisión
  const assignValidator = async (validatorId: number, notes?: string) => {
    if (!selectedDocket.value) return false
    isResolving.value = true
    actionFeedback.value = null

    try {
      const res = await api.put(`/staff/verifications/${selectedDocket.value.id}/assign`, {
        validator_id: validatorId,
        notes: notes || 'Inspector asignado desde Mesa de Supervisión.',
      })

      actionFeedback.value = {
        type: 'modify',
        message: res.data?.message || 'Inspector asignado exitosamente.',
      }

      await fetchDockets()
      return true
    } catch (err: any) {
      console.error('Error al asignar inspector:', err)
      return false
    } finally {
      isResolving.value = false
    }
  }

  return {
    user,
    dockets,
    metrics,
    isLoading,
    isResolving,
    actionFeedback,
    activeTab,
    searchQuery,
    selectedValidatorFilter,
    selectedDepartmentFilter,
    selectedDocketId,
    selectedDocket,
    filteredDockets,
    availableValidators,
    fetchDockets,
    resolveDocket,
    assignValidator,
  }
}
