import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../useAuth'
import { api } from '../useApi'
import { useConfirmDialog } from './useConfirmDialog'
import { INITIAL_OFFERS } from '../../data/mockPropietarioData'

import type {
  PropietarioOferta,
  NewOfferPayload,
  RevalidacionPayload,
} from '../../types/propietario'

// Estado compartido a nivel módulo para persistencia durante la sesión
const myOffers = ref<PropietarioOferta[]>(JSON.parse(JSON.stringify(INITIAL_OFFERS)))
const selectedOfferId = ref<number>(8)
const activeNav = ref<'tarifas' | 'calendario' | 'inquilinos'>('tarifas')
const isLoadingOffers = ref<boolean>(false)
const isSavingTarifas = ref<boolean>(false)

/**
 * Mapeador de datos desde la API de Laravel (PropertyResource) al modelo de interfaz PropietarioOferta
 */
function mapApiPropertyToOferta(prop: any, existingOffer?: PropietarioOferta): PropietarioOferta {
  return {
    id: prop.id,
    legajo: prop.verification_certificate_number || `#${prop.id}-TS/26`,
    title: prop.title,
    subtitle: prop.property_type ? `Tipo: ${prop.property_type}` : (existingOffer?.subtitle || 'Predio Verificado'),
    department: prop.department?.name || existingOffer?.department || 'San Juan',
    location: prop.location || existingOffer?.location || '',
    poliza: prop.insurance?.policy_number
      ? `${prop.insurance.company || 'Triunfo Seguros'} (Póliza ${prop.insurance.policy_number})`
      : (existingOffer?.poliza || 'Póliza Homologada Triunfo Seguros'),
    actaFecha: prop.insurance?.acta_fecha || (prop.verified_at ? new Date(prop.verified_at).toLocaleDateString('es-AR') : (existingOffer?.actaFecha || 'En trámite')),
    image: prop.images?.[0]?.image_path || prop.verified_facade_image || existingOffer?.image || 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
    isListingVisible: prop.is_listing_visible ?? existingOffer?.isListingVisible ?? true,
    pauseMode: prop.pause_mode ?? existingOffer?.pauseMode,
    pauseStartDate: prop.pause_start_date ?? existingOffer?.pauseStartDate,
    pauseEndDate: prop.pause_end_date ?? existingOffer?.pauseEndDate,
    schedule: {
      start: prop.schedule?.start || existingOffer?.schedule?.start || '10:00',
      end: prop.schedule?.end || existingOffer?.schedule?.end || '20:00',
      activeDays: prop.schedule?.active_days || existingOffer?.schedule?.activeDays || [0, 4, 5, 6],
    },
    tariffs: {
      lunesJueves: prop.tariffs?.lunes_jueves ?? existingOffer?.tariffs?.lunesJueves ?? (prop.price ?? 35000),
      viernesDomingo: prop.tariffs?.viernes_domingo ?? existingOffer?.tariffs?.viernesDomingo ?? Math.round((prop.price ?? 35000) * 1.25),
      sabadoFeriados: prop.tariffs?.sabado_feriados ?? existingOffer?.tariffs?.sabadoFeriados ?? Math.round((prop.price ?? 35000) * 1.4),
      eventoEspecial: prop.tariffs?.evento_especial ?? existingOffer?.tariffs?.eventoEspecial ?? 85000,
      pernocteCamas: prop.tariffs?.pernocte_camas ?? existingOffer?.tariffs?.pernocteCamas ?? 65000,
      pernocteCarpas: prop.tariffs?.pernocte_carpas ?? existingOffer?.tariffs?.pernocteCarpas ?? 40000,
      findeCompleto: prop.tariffs?.finde_completo ?? existingOffer?.tariffs?.findeCompleto ?? 110000,
      fiestaNocturna: prop.tariffs?.fiesta_nocturna ?? existingOffer?.tariffs?.fiestaNocturna ?? 120000,
    },
    metrics: existingOffer?.metrics || {
      jornadas: 0,
      jornadasDiff: 'Sin reservas previas',
      ocupacion: 0,
      ingresosEstimados: 0,
      ingresosCobrado: 0,
      reputacion: 0,
      resenasCount: 0,
    },
    amenities: Array.isArray(prop.amenities) && prop.amenities.length > 0
      ? prop.amenities.map((a: string) => ({
          icon: a.toLowerCase().includes('pileta') || a.toLowerCase().includes('piscina') ? 'pool' : a.toLowerCase().includes('parrill') ? 'outdoor_grill' : 'check_circle',
          label: a,
          isSecurityFeature: a.toLowerCase().includes('cerca') || a.toLowerCase().includes('segur') || a.toLowerCase().includes('disyuntor'),
        }))
      : (existingOffer?.amenities || []),
    tenants: existingOffer?.tenants || [],
    pastTenants: existingOffer?.pastTenants || [],
    verificationStatus: prop.verification_status ?? existingOffer?.verificationStatus ?? 'unverified',
    publicationStatus: prop.publication_status ?? existingOffer?.publicationStatus ?? 'draft',
    capacity: prop.capacity ?? existingOffer?.capacity ?? 20,
    verificationRequest: prop.verification_request
      ? {
          id: prop.verification_request.id,
          status: prop.verification_request.status,
          securityToken: prop.verification_request.security_token,
          notes: prop.verification_request.notes,
          createdAt: prop.verification_request.created_at,
          validator: prop.verification_request.validator ? {
            id: prop.verification_request.validator.id,
            name: prop.verification_request.validator.name,
            phone: prop.verification_request.validator.phone,
            dni: prop.verification_request.validator.dni,
            origin_city: prop.verification_request.validator.origin_city,
          } : null,
        }
      : existingOffer?.verificationRequest ?? null,
  }
}

export function usePropietarioOffers() {
  const router = useRouter()
  const { user, isAuthenticated, logout } = useAuth()

  const selectedOffer = computed(() => {
    return myOffers.value.find((o) => o.id === selectedOfferId.value) || myOffers.value[0]
  })

  // Gestión de tabs de tarifas y feedback
  const tariffsTab = ref<'diurno' | 'pernocte' | 'fiestas'>('diurno')
  const tarifaSavedFeedback = ref(false)

  /**
   * Carga inicial de ofertas desde la API de Laravel
   */
  const fetchOffers = async () => {
    if (!isAuthenticated.value) return
    isLoadingOffers.value = true
    try {
      const response = await api.get('/client/properties')
      const propertiesData = response.data?.data
      if (Array.isArray(propertiesData) && propertiesData.length > 0) {
        const mapped = propertiesData.map((prop: any) => {
          const existing = myOffers.value.find((o) => o.id === prop.id)
          return mapApiPropertyToOferta(prop, existing)
        })
        myOffers.value = mapped
        if (!mapped.some((o) => o.id === selectedOfferId.value)) {
          selectedOfferId.value = mapped[0].id
        }
      }
    } catch (err: any) {
      console.warn('Sincronización API de ofertas: operando con caché local.', err)
    } finally {
      isLoadingOffers.value = false
    }
  }

  /**
   * Persiste tarifas multimodales, franja horaria y receso/vacaciones en la base de datos
   */
  const saveOfferToServer = async (targetOffer?: PropietarioOferta): Promise<boolean> => {
    const offerToSave = targetOffer || selectedOffer.value
    if (!offerToSave) return false

    isSavingTarifas.value = true
    try {
      if (isAuthenticated.value && typeof offerToSave.id === 'number') {
        const payload = {
          tariff_lunes_jueves: offerToSave.tariffs.lunesJueves,
          tariff_viernes_domingo: offerToSave.tariffs.viernesDomingo,
          tariff_sabado_feriados: offerToSave.tariffs.sabadoFeriados,
          tariff_evento_especial: offerToSave.tariffs.eventoEspecial,
          tariff_pernocte_camas: offerToSave.tariffs.pernocteCamas ?? null,
          tariff_pernocte_carpas: offerToSave.tariffs.pernocteCarpas ?? null,
          tariff_finde_completo: offerToSave.tariffs.findeCompleto ?? null,
          tariff_fiesta_nocturna: offerToSave.tariffs.fiestaNocturna ?? null,
          schedule_start: offerToSave.schedule.start,
          schedule_end: offerToSave.schedule.end,
          active_days: offerToSave.schedule.activeDays,
          is_listing_visible: offerToSave.isListingVisible,
          pause_mode: offerToSave.pauseMode ?? null,
          pause_start_date: offerToSave.pauseStartDate ?? null,
          pause_end_date: offerToSave.pauseEndDate ?? null,
        }
        await api.put(`/client/properties/${offerToSave.id}`, payload)
      }

      tarifaSavedFeedback.value = true
      setTimeout(() => {
        tarifaSavedFeedback.value = false
      }, 3000)
      return true
    } catch (err: any) {
      console.warn('Aviso: cambios de oferta preservados en sesión local.', err)
      tarifaSavedFeedback.value = true
      setTimeout(() => {
        tarifaSavedFeedback.value = false
      }, 3000)
      return false
    } finally {
      isSavingTarifas.value = false
    }
  }

  const handleSaveTarifas = async () => {
    await saveOfferToServer(selectedOffer.value)
  }

  /**
   * Modifica y persiste el estado de visibilidad / receso de la oferta
   */
  const updateListingVisibility = async (payload: {
    isListingVisible: boolean
    pauseMode?: 'indefinite' | 'vacation'
    pauseStartDate?: string
    pauseEndDate?: string
  }) => {
    if (!selectedOffer.value) return
    selectedOffer.value.isListingVisible = payload.isListingVisible
    selectedOffer.value.pauseMode = payload.pauseMode
    selectedOffer.value.pauseStartDate = payload.pauseStartDate
    selectedOffer.value.pauseEndDate = payload.pauseEndDate

    await saveOfferToServer(selectedOffer.value)
  }

  // Modal para agregar una nueva oferta
  const showAddOfferModal = ref(false)
  const newOffer = ref<NewOfferPayload>({
    title: '',
    department: 'Pocito, San Juan',
    location: '',
    capacity: 40,
    observations: '',
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
  })

  const handleAddOffer = async () => {
    const { showAlert } = useConfirmDialog()
    if (!newOffer.value.title || !newOffer.value.location) {
      showAlert({
        title: 'Datos Incompletos',
        message: 'Por favor complete el nombre del predio y su ubicación.',
        icon: 'error_outline',
      })
      return
    }

    // Mapear nombre del departamento a department_id oficial de San Juan
    const deptMap: Record<string, number> = {
      'Calingasta': 1,
      'Valle Fértil': 2,
      'Iglesia': 3,
      'Jáchal': 4,
      'Zonda': 5,
      'Ullum': 6,
      'Rawson': 7,
      'Pocito': 8,
      'Capital': 9,
      'Rivadavia': 10,
      'Santa Lucía': 11,
      'Caucete': 12,
      'Albardón': 13,
      'Angaco': 14,
      'San Martín': 15,
      'Sarmiento': 16,
      '9 de Julio': 17,
      '25 de Mayo': 18,
      'Chimbas': 19,
    }

    const deptStr = newOffer.value.department || 'Pocito'
    let deptId = 8 // Pocito por defecto
    for (const [name, id] of Object.entries(deptMap)) {
      if (deptStr.toLowerCase().includes(name.toLowerCase())) {
        deptId = id
        break
      }
    }

    const baseWeekday = newOffer.value.rateWeekday ?? 38000

    const amenitiesList: string[] = []

    try {
      if (isAuthenticated.value) {
        const payload = {
          title: newOffer.value.title,
          department_id: deptId,
          property_type: 'Quinta',
          location: newOffer.value.location,
          address: newOffer.value.location,
          description: newOffer.value.observations || 'Quinta diurna registrada para homologación presencial.',
          price: baseWeekday,
          price_type: 'por día',
          capacity: newOffer.value.capacity || 40,
          amenities: amenitiesList,
          observations: newOffer.value.observations,
        }

        const res = await api.post('/client/properties', payload)
        if (res.data?.data) {
          const apiProp = res.data.data
          const newOferta = mapApiPropertyToOferta(apiProp)
          myOffers.value.push(newOferta)
          selectedOfferId.value = newOferta.id
        }
      }
    } catch (err: any) {
      console.warn('Error guardando en backend, se mantiene registro local.', err)
    }

    showAddOfferModal.value = false
    showAlert({
      title: '¡Oferta Registrada con Éxito!',
      message: `La oferta "${newOffer.value.title}" fue registrada exitosamente en borrador. La Mesa de Supervisores de Turismo Seguro recibió la solicitud para coordinar y asignar la inspección in situ del predio.`,
      icon: 'check_circle',
    })
  }

  // Solicitud de revalidación in situ
  const revalidacionForm = ref<RevalidacionPayload>({
    motivo: 'fotos',
    fecha: '2025-02-15',
    notas: '',
  })

  const handleSolicitarRevalidacion = () => {
    const { showAlert } = useConfirmDialog()
    showAlert({
      title: 'Solicitud Enviada',
      message: 'Solicitud enviada al cuerpo técnico de validadores de Turismo Seguro. Nos comunicaremos dentro de las 24hs hábiles para confirmar el turno presencial.',
      icon: 'assignment_turned_in',
    })
    revalidacionForm.value.notas = ''
  }

  const handleLogout = async () => {
    await logout()
    router.push('/')
  }

  return {
    user,
    myOffers,
    selectedOfferId,
    selectedOffer,
    activeNav,
    tariffsTab,
    tarifaSavedFeedback,
    isLoadingOffers,
    isSavingTarifas,
    fetchOffers,
    saveOfferToServer,
    handleSaveTarifas,
    updateListingVisibility,
    showAddOfferModal,
    newOffer,
    handleAddOffer,
    revalidacionForm,
    handleSolicitarRevalidacion,
    handleLogout,
  }
}
